<?php

if (! function_exists('current_route_name')) {
    function current_route_name(?string $is_same_as = null): string|bool
    {
        /** @phpstan-ignore-next-line */
        $current_route_name = \Illuminate\Support\Facades\Request::route()->getName();

        if (! is_null($is_same_as)) {
            return $current_route_name === $is_same_as;
        }

        return $current_route_name;
    }

}
