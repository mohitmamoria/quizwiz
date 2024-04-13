<?php

if (! function_exists('u')) {
    /**
     * Returns a user by their ID
     *
     * @param  int  $id
     * @return App\User
     */
    function u($id)
    {
        return App\Models\User::find($id);
    }
}
