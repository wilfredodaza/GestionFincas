<?php

namespace App\Controllers;

use App\Models\Navbar;
use App\Models\Inicio;
use App\Models\Servicio;
use App\Models\CasoExito;
use App\Models\Slider;
use App\Models\PiePagina;
use App\Models\Nosotros;
use App\Models\Elegirnos;
use App\Models\Pregunta;
use App\Models\Head;
use App\Models\OptionBlog;
use App\Models\DetailBlog;
use App\Models\Agenda;
use App\Models\CategoriaBlog;

class HomeController extends BaseController
{

	public function home()
	{
    return  view('landings/home');
	}

}
