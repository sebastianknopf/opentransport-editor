<?php

namespace App\Controller\Admin;


use Cake\Console\ShellDispatcher;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

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

        $this->loadComponent('Authentication.Authentication');
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

    /**
     * Creates an information about all jobs. If only certain jobs
     * should be queried, specify one or more job comma-separated job IDs.
     *
     * @param null $jobIds The job IDs to request
     */
    public function jobStatus($jobIds = null)
    {
        $jobIds = $jobIds != null ? explode(',', $jobIds) : [];
        $jobsTable = TableRegistry::getTableLocator()->get('Queue.QueuedJobs');

        $incompleteTasks = $jobsTable->find();
        if (count($jobIds) > 0) {
            $incompleteTasks->where(['QueuedJobs.id' => $jobIds], ['QueuedJobs.id' => 'integer[]']);
        }

        $queuedJobs = [];
        foreach ($incompleteTasks->toArray() as $jobInfo) {
            array_push($queuedJobs, [
                'id' => $jobInfo->id,
                'type' => $jobInfo->job_type,
                'pending' => $jobInfo->fetched == null,
                'running' => $jobInfo->fetched != null && $jobInfo->completed == null,
                'completed' => $jobInfo->completed != null,
                'failed' => $jobInfo->failed > 0,
                'progress' => $jobInfo->progress,
                'status' => $jobInfo->status
            ]);
        }

        $this->set('result', ['queuedJobs' => $queuedJobs]);

        $this->RequestHandler->renderAs($this, 'json');
        $this->set('_serialize', 'result');
    }

    /**
     *
     */
    public function startQueueWorker()
    {
        $this->request->allowMethod(['post', 'put', 'patch']);

        ignore_user_abort(true);

        if (!empty(session_id())) {
            session_write_close();
        }

        if (is_callable('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } else {
            http_response_code(200);
            header('Connection: close');
            header('Content-Length: 0');
            header('Content-Encoding: none');

            ob_end_flush();
            ob_flush();
            flush();
        }

        if ($this->request->hasHeader('User-Agent') && $this->request->getHeader('User-Agent')[0] == Configure::read('App.name')) {
            $dispatcher = new ShellDispatcher();
            $dispatcher->run(['cake', 'queue', 'runworker']);
        }
    }
}