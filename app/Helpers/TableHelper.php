<?php

// app/Helpers/TableHelper.php
if (!function_exists('sortLink')) {
    function sortLink($column, $label)
    {
        $currentSort = request('sort');
        $currentDirection = request('direction', 'asc');

        $isCurrent = $currentSort === $column;
        $newDirection = ($isCurrent && $currentDirection === 'asc') ? 'desc' : 'asc';

        $icon = $isCurrent 
            ? ($currentDirection === 'asc' ? ' ↑' : ' ↓') 
            : '';

        $url = request()->fullUrlWithQuery([
            'sort' => $column,
            'direction' => $newDirection,
        ]);

        return '<a href="' . $url . '" style="color:inherit; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">' 
               . $label . $icon . '</a>';
    }
}