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

use Illuminate\Support\Facades\Gate;
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

        $this->registerPolicies();
    }

    public function registerPolicies()
    {
        \AccessControl::define('Tin tức - Xem danh sách tin', 'admin.news.index');
        \AccessControl::define('Tin tức - Thêm tin', 'admin.news.create');
        \AccessControl::define('Tin tức - Sửa tin', 'admin.news.edit');
        \AccessControl::define('Tin tức - Ẩn tin', 'admin.news.disable');
        \AccessControl::define('Tin tức - Công khai thin', 'admin.news.enable');
        \AccessControl::define('Tin tức - Xóa tin', 'admin.news.destroy');

        \AccessControl::define('Tin tức - Xem danh sách danh mục', 'admin.news.category.index');
        \AccessControl::define('Tin tức - Thêm danh mục mới', 'admin.news.category.create');
        \AccessControl::define('Tin tức - Sửa danh mục', 'admin.news.category.edit');
        \AccessControl::define('Tin tức - Xóa danh mục', 'admin.news.category.destroy');
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
