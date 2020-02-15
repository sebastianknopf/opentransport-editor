<?php

namespace App\Controller\Api;

use App\Controller\BaseController;
use App\Model\Entity\ApiLog;
use App\Utility\LocaleList;
use App\View\RestView;
use Authentication\Identifier\IdentifierInterface;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\I18n\I18n;
use Cake\Utility\Security;
use Cake\Utility\Xml;
use Firebase\JWT\JWT;

class OpenTransportApiController extends BaseController
{

    const API_VERSION = '1.0.0';

    const CONTENT_TYPE_JSON = 'json';
    const CONTENT_TYPE_XML = 'xml';

    /**
     * The result array for the HTTP response.
     *
     * @var _httpResponseResult
     */
    protected $_httpResponseResult;

    /**
     * The content type for the HTTP response
     *
     * @var _httpContentType
     */
    protected $_httpContentType;

    /**
     * The status code (e.g. 200) for the HTTP response
     *
     * @var _httpStatusCode
     */
    protected $_httpStatusCode;

    /**
     * Basic initialisations like authentication and date / time format.
     *
     * @throws \Exception
     */
    public function initialize()
    {
        parent::initialize();

        // initialize string representation of date and time objects
        FrozenDate::setJsonEncodeFormat('yyyy-MM-dd');
        FrozenDate::setToStringFormat('yyyy-MM-dd');

        FrozenTime::setJsonEncodeFormat('HH:mm:ss');
        FrozenTime::setToStringFormat('HH:mm:ss');

        // enable authentication
        $this->loadComponent('Authentication.Authentication');
        $this->Authentication->allowUnauthenticated(['index', 'auth', 'stops', 'routes', 'trips']);
    }

    /**
     * Read the extension form route and set it as HTTP content type.
     *
     * @param Event $event The fired event object.
     * @return \Cake\Http\Response|null Return value for parent function.
     */
    public function beforeFilter(Event $event)
    {
        $this->_httpContentType = $this->request->getParam('_ext');

        return parent::beforeFilter($event);
    }

    /**
     * Stop rendering a view file, take up the HTTP information and pass it back to the
     * client as JSON or XML response.
     *
     * @param Event $event The fired event object.
     * @return \Cake\Http\Response|\Psr\Http\Message\MessageInterface|null The intercepted response instead of a view content.
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        // skip output formatting if only the index page should be shown
        if ($this->getRequest()->getParam('action') == 'index') {
            return;
        }

        // build response depending on desired content type
        $body = $this->response->getBody();
        if ($this->_httpContentType == self::CONTENT_TYPE_JSON) {
            $body->write(json_encode([
                'status' => $this->_httpStatusCode,
                'message' => 'OK',
                'result' => $this->_httpResponseResult
            ]));
        } elseif ($this->_httpContentType == self::CONTENT_TYPE_XML) {
            $body->write(Xml::fromArray([
                'response' => [
                    'status' => $this->_httpStatusCode,
                    'message' => 'OK',
                    'result' => $this->_httpResponseResult
                ]
            ], 'tags')->asXML());
        } else {
            throw new BadRequestException('unknown response format ' . $this->_httpContentType);
        }

        // set body, content type and status
        // optionally log the request information
        $this->response = $this->response->withType($this->_httpContentType)->withBody($body)->withStatus($this->_httpStatusCode);
        if (Configure::read('RestAPI.logRequests') && (Configure::read('RestAPI.logCodes') == '*' || in_array(200, Configure::read('RestAPI.logCodes')))) {
            ApiLog::add($this->request, $this->response);
        }

        // return ready build response with CORS enabled
        return $this->response->cors($this->request)
            ->allowOrigin(Configure::read('RestAPI.CORS.allowOrigins'))
            ->allowMethods(Configure::read('RestAPI.CORS.allowMethods'))
            ->allowHeaders(Configure::read('RestAPI.CORS.allowHeaders'))
            ->maxAge(Configure::read('RestAPI.CORS.maxAge'))
            ->allowCredentials(['true'])
            ->build();
    }

    /**
     * Index method. Provides API overview via template.
     */
    public function index()
    {
        $this->viewBuilder()->setLayout('frontend');
    }

    /**
     * Authentication method for other REST API calls. Expects parameters
     * $username and $password in post body and creates a JWT token if the
     * credentials are valid.
     */
    public function auth()
    {
        $this->request->allowMethod(['POST', 'PUT']);

        // read data from post body depending on content type
        $data = $this->request->getData();
        if ($this->_httpContentType == self::CONTENT_TYPE_JSON) {
            $data = $this->request->input('json_decode');
        } elseif ($this->_httpContentType == self::CONTENT_TYPE_XML) {
            $data = $this->request->input('Cake\Utility\Xml::build');
        }

        // extract username and password
        $username = $data->username;
        $password = $data->password;

        if ($username == null || $password == null) {
            throw new BadRequestException('missing either parameter username or parameter password');
        }

        // check credentials, throw an 401 exception if invalid
        $this->loadModel('Users');
        $user = $this->Users->findByUsername($username)->first();
        if ($user == null || !password_verify($password, $user->password)) {
            throw new UnauthorizedException('invalid username or password');
        }

        // generate JWT token and pass back to client
        $payload = [IdentifierInterface::CREDENTIAL_JWT_SUBJECT => ['id' => $user->id]];
        $token = JWT::encode($payload, Security::getSalt());

        $this->httpResponse(['token' => $token]);
    }

    /**
     * Stops method to find all stops based on desired finder method
     * and passed query params.
     *
     * @param $selector The finder for the stops table.
     */
    public function stops($selector)
    {
        $this->request->allowMethod(['GET']);

        $this->loadModel('Stops');
        $stops = $this->Stops->find($selector, [
            'query' => $this->request->getQueryParams()
        ])->where([
            'Stops.location_type' => 1 // select only stops of type STATION = 1
        ]);

        $stops = $stops->toArray();

        // prettify for xml output
        if ($this->_httpContentType == self::CONTENT_TYPE_XML) {
            $stops = ['stop' => $stops];
        }

        $this->httpResponse(['stops' => $stops]);
    }

    /**
     * Routes method to find all routes based on desired finder method
     * and passed query params.
     *
     * @param $selector The finder for the routes table.
     */
    public function routes($selector)
    {
        $this->request->allowMethod(['GET']);

        $this->loadModel('Routes');
        $routes = $this->Routes->find($selector, [
            'query' => $this->request->getQueryParams()
        ])->contain([
            'Agencies' // contain related agencies by default
        ]);

        $routes = $routes->toArray();

        // prettify for xml output
        if ($this->_httpContentType == self::CONTENT_TYPE_XML) {
            $routes = ['route' => $routes];
        }

        $this->httpResponse(['routes' => $routes]);
    }

    /**
     * Trips method to find all trips based on desired finder method
     * and passed query params.
     *
     * @param $selector The finder for the trips table.
     */
    public function trips($selector)
    {
        $this->request->allowMethod(['GET']);

        // the 'all'-selector is not allowed due to a too high amount of data
        if ($selector == 'all') {
            throw new BadRequestException('selector not allowed');
        }

        $this->loadModel('Trips');
        $trips = $this->Trips->find($selector, [
            'query' => $this->request->getQueryParams()
        ])->order([
            'Trips.start_time ASC' // sort by trips start time in general
        ]);

        $trips = $trips->toArray();

        // if there's a time parameter set, include only trips in result which are departing
        // after this time parameter. Due to the frequency option this selection can not be done
        // in the finder method by SQL.
        $refTime = $this->request->getQuery('time') ? FrozenTime::createFromFormat('H:i:s', $this->request->getQuery('time')) : null;
        if ($refTime != null) {
            $tmpTrips = []; // container for all included trips
            for ($t = 0; $t < count($trips); $t++) {
                // select the time reference of the trip
                // by figure of this time reference we'll decide whether the trip will be contained in result or not
                $tripTime = $trips[$t]->start_time; // use the start time in general
                if ($selector == 'byStopId' && $trips[$t]->_matchingData != null && isset($trips[$t]->_matchingData['StopTimes'])) {
                    $tripTime = $trips[$t]->_matchingData['StopTimes']->departure_time; // if the trip is found by stopId, the departure time of the matching stop must be used
                }

                // processing begins here
                if ($refTime > $tripTime) {
                    // check if there are frequencies defined
                    if ($trips[$t]->frequencies != null && count($trips[$t]->frequencies) > 0) {
                        foreach ($trips[$t]->frequencies as $frequency) {
                            // if a frequency's range matches the time parameter, the trip can be shifted
                            // the difference between the trip's start time and the choosen trip time reference becomes subtracted
                            // from the time reference! This ensures that a frequency is included even if the trip time reference is after
                            // the end time of the frequency but the trips real start time within the desired range.
                            if ($frequency->start_time <= $refTime && $frequency->end_time >= $refTime->sub($trips[$t]->start_time->diff($tripTime))) {
                                // find the amount of minutes the trip must be shifted
                                // to have a departure time after the time parameter
                                $shiftMinutes = 0;
                                while ($tripTime < $refTime) {
                                    $tripTime = $tripTime->addMinutes($frequency->headway_secs / 60.0);
                                    $shiftMinutes += $frequency->headway_secs / 60.0;
                                }

                                // shift start and end time
                                $trips[$t]->start_time = $trips[$t]->start_time->addMinutes($shiftMinutes);
                                $trips[$t]->end_time = $trips[$t]->end_time->addMinutes($shiftMinutes);

                                // shift all stop times
                                for ($s = 0; $s < count($trips[$t]->stop_times); $s++) {
                                    $trips[$t]->stop_times[$s]->arrival_time = $trips[$t]->stop_times[$s]->arrival_time->addMinutes($shiftMinutes);
                                    $trips[$t]->stop_times[$s]->departure_time = $trips[$t]->stop_times[$s]->departure_time->addMinutes($shiftMinutes);
                                }

                                // finally add the trip to the result and break the loop,
                                // the trip is only needed once in result
                                array_push($tmpTrips, $trips[$t]);
                                break;
                            }
                        }
                    }
                } else {
                    // if a trip's reference is BEFORE the time parameter,
                    // the trip is included definitely
                    array_push($tmpTrips, $trips[$t]);
                }
            }

            // set the temporary results back to $trips
            $trips = $tmpTrips;
        }

        // if trips selected by stopId, throw away every stop time which is not related to the stop id
        // important for departure table functionality
        if ($selector == 'byStopId' && !empty($this->request->getQuery('stopId'))) {
            for ($t = 0; $t < count($trips); $t++) {
                if ($trips[$t]->_matchingData != null && isset($trips[$t]->_matchingData['StopTimes'])) {
                    // use matched stop as reference and pick up the stop time from matching data
                    $refStop = $trips[$t]->_matchingData['StopTimes']->stop_id;
                    $stopTime = $trips[$t]->_matchingData['StopTimes'];
                    for ($s = 0; $s < count($trips[$t]->stop_times); $s++) {
                        if ($trips[$t]->stop_times[$s]->stop_id == $refStop) {
                            $stopTime = $trips[$t]->stop_times[$s];
                        }
                    }

                    // set the final resulting stop time
                    $trips[$t]->stop_times = [$stopTime];
                }
            }
        }

        // prettify for xml output
        if ($this->_httpContentType == self::CONTENT_TYPE_XML) {
            for ($t = 0; $t < count($trips); $t++) {
                $trips[$t]->stop_times = ['stop_time' => $trips[$t]->stop_times];
                $trips[$t]->frequencies = ['frequency' => $trips[$t]->frequencies];

                if ($trips[$t]->shape != null) {
                    $trips[$t]->shape->points = ['point' => $trips[$t]->shape->points];
                }
            }

            $trips = ['trip' => $trips];
        }

        $this->httpResponse(['trips' => $trips]);
    }

    /**
     * Prepares the HTTP variables to be processed by beforeRender method.
     *
     * @param $httpResponseResult The result to display in the response object.
     */
    private function httpResponse($httpResponseResult)
    {
        $this->_httpStatusCode = 200;
        $this->_httpResponseResult = $httpResponseResult;
    }

}