<?php

namespace App\Controller\Admin;


use App\Controller\BaseController;
use App\Model\Entity\Service;
use App\Model\Entity\Trip;
use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Internal controller for AJAX calls from admin backend.
 *
 * Class AjaxController
 * @package App\Controller\Admin
 */
class AjaxController extends Controller
{
    /**
     * Initialize method. Load RequestHandlerComponent.
     *
     * @throws \Exception
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
    }

    /**
     * Find stops by their stopName or stopCode.
     */
    public function selectStopsByNameOrCode()
    {
        $this->loadModel('Stops');
        $stops = $this->Stops->find('search', [
            'search' => $this->request->getQueryParams()
        ]);

        $stops = $stops->toArray();
        for ($s = 0; $s < count($stops); $s++) {
            $stops[$s]->label = $stops[$s]->stop_name;

            if (!empty($stops[$s]->stop_code)) {
                $stops[$s]->label .= ' (' . $stops[$s]->stop_code . ')';
            }
        }

        $this->set('stops', $stops);
        $this->set('_serialize', 'stops');
    }

    /**
     * Select points of a shape by shapeId.
     */
    public function selectShapePointsById()
    {
        $this->loadModel('Shapes');
        $shape = $this->Shapes->get($this->request->getQuery('id'));

        $this->set('shape_points', $shape->decode($shape->shape_polyline));
        $this->set('_serialize', 'shape_points');
    }

    /**
     * Delete a service exception item by id. Only accepts POST / DELETE request.
     *
     * @param int $id The service exception id to be deleted
     */
    public function deleteServiceExceptionById($id)
    {
        $this->set('message', 'INVALID');

        if ($this->request->is(['post', 'delete'])) {
            $this->loadModel('ServiceExceptions');
            $exception = $this->ServiceExceptions->get($id);

            if ($this->ServiceExceptions->delete($exception)) {
                $this->set('message', 'OK');
            } else {
                $this->set('message', 'ERROR');
            }
        }

        $this->RequestHandler->renderAs($this, 'json');
        $this->set('_serialize', 'message');
    }

    /**
     * Delete a stop time item by id. Only accepts POST / DELETE request.
     *
     * @param int $id The stop time id to be deleted
     */
    public function deleteStopTimeById($id)
    {
        $this->set('message', 'INVALID');

        if ($this->request->is(['post', 'delete'])) {
            $this->loadModel('StopTimes');
            $stoptime = $this->StopTimes->get($id);

            if ($this->StopTimes->delete($stoptime)) {
                $this->set('message', 'OK');
            } else {
                $this->set('message', 'ERROR');
            }
        }

        $this->RequestHandler->renderAs($this, 'json');
        $this->set('_serialize', 'message');
    }

    /**
     * Delete a frequency item by id. Only accepts POST / DELETE request.
     *
     * @param int $id The frequency id to be deleted
     */
    public function deleteFrequencyById($id)
    {
        $this->set('message', 'INVALID');

        if ($this->request->is(['post', 'delete'])) {
            $this->loadModel('Frequencies');
            $frequency = $this->Frequencies->get($id);

            if ($this->Frequencies->delete($frequency)) {
                $this->set('message', 'OK');
            } else {
                $this->set('message', 'ERROR');
            }
        }

        $this->RequestHandler->renderAs($this, 'json');
        $this->set('_serialize', 'message');
    }
}