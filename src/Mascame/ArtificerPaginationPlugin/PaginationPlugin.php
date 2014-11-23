<?php namespace Mascame\ArtificerPaginationPlugin;

use Mascame\Artificer\Model\Model;
use Mascame\Artificer\Plugin\AbstractPlugin;
use Event;
use Input;
use Session;
use App;
use View;

class PaginationPlugin extends AbstractPlugin {

	public static $pagination;
	public static $per_page_key;

	public function meta()
	{
		$this->version = '1.0';
		$this->name = 'Pagination';
		$this->description = 'Provides Laravel pagination to models';
		$this->author = 'Marc Mascarell';
	}


	public function boot()
	{
		self::$per_page_key = $this->namespace .'.per_page' . '.' . Model::getCurrent();
		self::$pagination = $this->getPagination();

		App::make('paginator')->setViewName($this->option->get('view'));

		View::share('artificer_pagination', self::$pagination);

		$this->addHooks();
	}

	public function addHooks()
	{
		Event::listen(array('artificer.view.all.before.showList', 'artificer.view.all.after.showList'), function ($model, $items) {
			print $items->appends(Input::except('page'))->links();
		});
	}

	/**
	 * @return mixed
	 */
	public function getPagination()
	{
		if (Session::has(self::$per_page_key)) {
			return Session::get(self::$per_page_key);
		}

		$items_per_page = $this->option->get('per_page');
		Session::set(self::$per_page_key, $items_per_page);

		return $items_per_page;
	}


	/**
	 * @param $number
	 */
	public static function setPagination($number, $modelName)
	{
		self::$pagination = $number;
		Session::set(self::$per_page_key, $number);
	}

}