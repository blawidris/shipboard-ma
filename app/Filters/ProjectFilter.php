<?php

namespace App\Filters;

use App\Models\Project;

class ProjectFilter extends QueryFilter
{
    /**
     * Filter project by status.
     *
     * @param string $code
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function status($code)
    {
        if ($code === 'ongoing') {
            return $this->builder->ongoing();
        }

        if ($code === 'overdue') {
            return $this->builder->overdue();
        }

        if ($code === 'completed') {
            return $this->builder->completed();
        }

        if ($code === 'archived') {
            return $this->builder->onlyTrashed();
        }
    }

    /**
     * Filter projects by search query.
     *
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function search($query)
    {
        if (strlen($query) > 1) {
            return $this->builder->where('name', 'LIKE', "%$query%")
                ->orWhere('description', 'LIKE', "%$query%");
        }
    }

    /**
     * Filter projects by visibility.
     *
     * @param string $code
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function visibility($code)
    {
        if ($code === 'only_me') {
            return $this->builder->where('visibility', Project::VISIBILITY_ONLY_ME);
        }

        if ($code === 'team_members') {
            return $this->builder->where('visibility', Project::VISIBILITY_TEAM_MEMBERS);
        }

        if ($code === 'organization') {
            return $this->builder->where('visibility', Project::VISIBILITY_ORGANIZATION);
        }
    }

    /**
     * Filter projects by due date.
     *
     * @param string $date
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function end_date($date)
    {
        return $this->builder->where('end_date', $date);
    }

    public function timeline($date)
    {
        $date = explode(" ", $date);
        $start_date = $date[0];
        $end_date = $date[0];

        return $this->builder->whereBetween('start_date', [$start_date, $end_date])->orWhereBetween('end_date', [$start_date, $end_date]);
    }
}
