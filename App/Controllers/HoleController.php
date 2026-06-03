<?php

namespace App\Controllers;

use App\Models\Hole;
use App\Router;
use App\Services\HoleService;

class HoleController extends Controller
{
    public function indexbyadmin($params)
    {
        $filter = isset($params['filter']) ? $params['filter'] : '';

        if ($filter == 'open') {
            $filter = 'status="відкритий" OR status="available"';
        } elseif ($filter == 'under_renovation') {
            $filter = 'status="на ремонті"';
        } elseif ($filter == 'all') {
            $filter = '';
        }
        $queryParams = [];

        if (!empty($filter)) {
            $queryParams['filter'] = $filter;
        }
        $holes = Hole::all($queryParams);
        self::render('Зали', '/admin/holes', 'admin', [
            'holes' => $holes,
            'filter' => $filter,
        ]);
    }

    public function holesbycashier($params)
    {
        $filter = isset($params['filter']) ? $params['filter'] : '';

        if ($filter == 'open') {
            $filter = 'status="відкритий" OR status="available"';
        } elseif ($filter == 'under_renovation') {
            $filter = 'status="на ремонті"';
        } elseif ($filter == 'all') {
            $filter = '';
        }
        $queryParams = [];

        if (!empty($filter)) {
            $queryParams['filter'] = $filter;
        }
        
        $holes = Hole::all($queryParams);
        for ($i = 0; $i < count($holes); ++$i) {
            $hole=new Hole($holes[$i]['id']);
            $holes[$i]['seanses'] = $hole->seansesOnDay();
            $holes[$i]['current_seanse'] = $hole->currentSeanse();
        }
        self::render('Зали', '/cashier/holes', 'cashier', [
            'holes' => $holes,
            'filter' => $filter,
        ]);
    }

    public function createbyadmin()
    {
        self::render('Додавання зали', '/admin_form/hole_add', 'admin');
    }

    public function storeholebyadmin($data)
    {
        HoleService::storeHole($data);
        Router::redirect('/admin/holes');
    }

    public function editbyadmin($params)
    {
        self::render('Редагування зали', '/admin_form/hole_edit', 'admin', [
            'hole' => new Hole($params['id']),
        ]);
    }

    public function updateholebyadmin($params, $data)
    {
        HoleService::updateHole($params['id'], $data);
        Router::redirect('/admin/holes');
    }
}
