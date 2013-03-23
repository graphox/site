<?php

/**
 * The repository to fetch user's timelines and the timelines he is following.
 * @package Graphox\Timeline
 * @author killme
 */

namespace Graphox\Timeline;

use HireVoice\Neo4j\Repository as BaseRepository;
use \Yii;

/**
 * The repository to fetch user's timelines and the timelines he is following.
 * @package Graphox\Timeline
 */
class TimelineRepository extends BaseRepository
{

    /**
     * Returns the user's private timeline.
     * @param \Graphox\Modules\User\User $user
     * @return \Graphox\Timeline\Timeline
     * @todo timeline factory?
     */
    public function findPersonalTimeline(\Graphox\Modules\User\User $user)
    {
        $timeline = $user->getPrimaryTimeline();

        //Lazy loading timeline
        if ($timeline === null)
        {
            $timeline = new Timeline;
            $timeline->setIsPublic(false);
            $timeline->setIsPrimary(true);
            $user->addTimeline($timeline);
            Yii::app()->neo4j->persist($user);
            Yii::app()->neo4j->flush();
        }



        return $timeline;
    }

    /**
     * Returns the updates from the timeline.
     * @usedBy TimelineIterator
     * @see TimelineIterator
     * @param mixed $timelines
     * @return array
     */
    public function findUpdates($timelines)
    {
        return Yii::app()->neo4j->createCypherQuery()
                        ->startWithNode('timeline', $timelines)
                        ->match('timeline -[:last|next*]-> x')
                        ->end('x')
                        ->getList();
    }

    /**
     * Returns the timelines the user is following.
     * @todo implement
     * @param \Graphox\Modules\User\User $user
     * @return type
     */
    public function findFollowingTimelines(\Graphox\Modules\User\User $user)
    {
        return array(
);
    }

}

