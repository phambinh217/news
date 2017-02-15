<?php
/**
 * ModuleAlias: news
 * ModuleName: news
 * Description: This is the first file run of module. You can assign bootstrap or register module services
 * @author: noname
 * @version: 1.0
 * @package: PhambinhCMS
 */
namespace Phambinh\News\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'News');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'News');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Merge configs
        if (\File::exists(__DIR__ . '/../../config/config.php')) {
            $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'news');
        }

        // Load helper
        if (\File::exists(__DIR__ . '/../../helper/helper.php')) {
            include __DIR__ . '/../../helper/helper.php';
        }

        // Load route
        if (!$this->app->routesAreCached()) {
            if (\File::exists(__DIR__ . '/../../routes.php')) {
                include __DIR__ . '/../../routes.php';
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        \Module::registerFromJsonFile('appearance', __DIR__ .'/../../module.json');
        \Menu::registerType('Danh mục tin', \Phambinh\News\Models\Category::class);

        add_action('app.init', function () {
            \AccessControl::register('news.manage', [
                
                'admin.news.index',
                'admin.news.create',
                'admin.news.edit',

                'admin.news.store',
                'admin.news.update',
                'admin.news.enable',
                'admin.news.disable',

                ], ['extend' => 'admin.base', 'label' => 'Quản lí tin tức']);

            \AccessControl::register('news.delete', [
                'admin.news.destroy',
                ], ['extend' => 'admin.base', 'label' => 'Xóa tin tức']);

            \AccessControl::register('news.category', [
                'admin.news.category.index',
                'admin.news.category.create',
                'admin.news.category.edit',

                'admin.news.category.store',
                'admin.news.category.update',
                'admin.news.category.disable',
                'admin.news.category.enable',
                'admin.news.category.destroy',

                ], ['extend' => 'admin.base', 'label' => 'Quản lí danh mục tin tức']);
        });

        add_action('admin.init', function () {
            \AdminMenu::register('news', [
                'parent'    =>  'main-manage',
                'label'     =>  'Tin tức',
                'url'       =>  admin_url('news'),
                'icon'      =>  'icon-notebook',
                'order' => '1',
            ]);

            \AdminMenu::register('news.create', [
                'parent'    =>  'news',
                'label'     =>  'Thêm tin tức mới',
                'url'       =>  admin_url('news/create'),
                'icon'      =>  'icon-note',
            ]);

            \AdminMenu::register('news.all', [
                'parent'    =>  'news',
                'label'     =>  'Tất cả tin tức',
                'url'       =>  admin_url('news'),
                'icon'      =>  'icon-magnifier',
            ]);

            \AdminMenu::register('news.category', [
                'parent'    =>  'news',
                'label'     =>  'Danh mục tin tức',
                'url'       =>  admin_url('news/category'),
                'icon'      =>  'icon-list',
            ]);
        });
    }
}
