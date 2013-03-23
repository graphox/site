<?php

/**
 * The executor/creator of a verb.
 * @package Graphox\Timeline
 * @author killme
 */

namespace Graphox\Timeline;

/**
 * @package Graphox\Timeline
 */
interface IHaveExecutor
{

    /**
     * Returns the user that has created/done this.
     * @return \Graphox\Modules\User\User
     */
    public function getExecutor();

    /**
     * Set the user that has created / done this.
     * @param \Graphox\Modules\User\User $user
     */
    public function setExecutor(\Graphox\Modules\User\User $user);
}

