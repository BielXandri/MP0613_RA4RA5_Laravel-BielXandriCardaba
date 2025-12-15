<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

class FilmController extends Controller
{

    /**
     * Read films from storage
     */
    public static function readFilms(): array {
        $films = Storage::json('/public/films.json');
        return $films;
    }
    /**
     * List films older than input year 
     * if year is not infomed 2000 year will be used as criteria
     */
    public function listOldFilms($year = null)
    {        
        $old_films = [];
        if (is_null($year))
        $year = 2000;
    
        $title = "Listado de Pelis Antiguas (Antes de $year)";    
        $films = FilmController::readFilms();

        foreach ($films as $film) {
        //foreach ($this->datasource as $film) {
            if ($film['year'] < $year)
                $old_films[] = $film;
        }
        return view('films.list', ["films" => $old_films, "title" => $title]);
    }
    /**
     * List films younger than input year
     * if year is not infomed 2000 year will be used as criteria
     */
    public function listNewFilms($year = null)
    {
        $new_films = [];
        if (is_null($year))
            $year = 2000;

        $title = "Listado de Pelis Nuevas (Después de $year)";
        $films = FilmController::readFilms();

        foreach ($films as $film) {
            if ($film['year'] >= $year)
                $new_films[] = $film;
        }
        return view('films.list', ["films" => $new_films, "title" => $title]);
    }
    /**
     * Lista TODAS las películas o filtra x año.
     */
    public function listFilmsByYear($year = null)
    {
        $films_filtered = [];
        $title = "Listado de todas las pelis filtrado por año $year";
 
        $films = FilmController::readFilms();
        
        if (is_null($year)) {
            $title = "Listado de todas las películas (Filtralas por año)";
            return view("films.list", ["films" => $films, "title" => $title]);
        }

        foreach ($films as $film) {
            if ($film['year'] == $year) {
                $films_filtered[] = $film;
            }
        }
        $title = "Listado de peliculas del año $year";
        return view("films.list", ["films" => $films_filtered, "title" => $title]);
    }
 
    /**
     * List films filtered by genre
     */
    public function listFilmsByGenre($genre = null)
    {
        $films_filtered = [];
        $title = "Listado de todas las pelis filtrado por género ";

        $films = FilmController::readFilms();


        if (is_null($genre)) {
            $title = "Listado de todas las películas (Filtralas por género))";
            return view("films.list", ["films" => $films, "title" => $title]);
        }

        $films = FilmController::readFilms();
        foreach ($films as $film) {
            if (strtolower($film['genre']) == strtolower($genre)) {
                $films_filtered[] = $film;
            }
        }
        $title = "Listado de peliculas del genero $genre";
        return view("films.list", ["films" => $films_filtered, "title" => $title]);
    }

    /**
 * Lista TODAS las películas ordenadas por año DESCENDENTE (de más nuevo a más antiguo).
 * Nueva ruta: /sortFilms
 */
public function sortFilmsByYear() {
    $films = FilmController::readFilms();
    $title = "Listado de todas las películas ordenadas por año (DESCENDENTE)";
    usort($films, function($a, $b) {
        $yearA = intval($a['year']);
        $yearB = intval($b['year']);
        return $yearB - $yearA;
    });
    return view('films.list', ['films' => $films,'title' => $title]);
} 
/**
 * Cuenta el número total de películas y lo devuelve en una vista de contador.
 * Nueva ruta: /filmout/countFilms
 */
public function countFilms() {

    $films = self::readFilms();
    $count = count($films);
    $title = "Contador de Películas";

    return view('films.count', ['count' => $count, 'title' => $title]);
}

}
