<?php

namespace User\Controllers;

use \Yii;
use \Doctrine;

class TimelineController extends \Controller
{

    public function actionIndex()
    {
        Yii::app()->user->isGuest && $this->redirect(array(
                    '/user/auth/login'));

        /**
         * Fetch the primary timeline of the current user.
         * (the timeline that contains it's private information)
         */
        $user = Yii::app()->neo4j->find('\Graphox\Modules\User\User',
                Yii::app()->user->id);

        if ($user == null) throw new \CException("Invalid user session.");

        $updates = \Graphox\Timeline\TimelineIterator::createFromUser($user);


        /**
         * TODO:
         * - find all users in the timelines
         * - find most active timeline
         * - draw timelines
         */
        $this->render('index', array(
            'updates' => $updates));
    }

    public function actionShare()
    {
        Yii::app()->user->isGuest && $this->redirect(array(
                    '/user/auth/login'));

        /**
         * Fetch the primary timeline of the current user.
         * (the timeline that contains it's private information)
         */
        $user = Yii::app()->neo4j->find('\Graphox\Modules\User\User',
                Yii::app()->user->id);

        if ($user == null) throw new \CException("Invalid user session.");

        //var_dump(Yii::app()->user->id);

        $timelineRepository = Yii::app()->neo4j->getRepository('\Graphox\Timeline\Timeline');
        $timeline = $timelineRepository->findPersonalTimeline($user);

        $share = new \Graphox\Verb\Share();
        $share->setContent('Hello world!');
        $share->setSource('Hello world!');
        $share->setIsPublished(true);
        $share->setCreatedDate(new \DateTime);

        $action = new \Graphox\Timeline\Action();
        $action->setVerb($share);

        $timeline->push($action);

        Yii::app()->neo4j->persist($timeline);
        Yii::app()->neo4j->flush();
    }

}