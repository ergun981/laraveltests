<?php
class Action extends Eloquent {
	/**
     * The name of the table associated with the model.
     *
     * @var string
     */
    public static $table = 'actions';

    /**
     * Indicates if the model has update and creation timestamps.
     *
     * @var bool
     */
    public static $timestamps = false;

    public function activities()
    {
        return $this->has_many('Activity');
    }
}