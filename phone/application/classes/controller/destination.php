<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Hotel
 * @desc 总控制器
 */
class Controller_Destination extends Stourweb_Controller
{
    private $_typeid = 12;   //产品类型
    private  $_cache_key = '';

    public function before()
    {
        parent::before();
        //检查缓存
        $this->_cache_key = Common::get_current_url();
        $html = Common::cache('get', $this->_cache_key);
        $genpage = intval(Arr::get($_GET, 'genpage'));
        if (!empty($html) && empty($genpage))
        {
            echo $html;
            exit;
        }
        $this->host_url = $GLOBALS['cfg_basehost'] . $GLOBALS['cfg_phone_cmspath'];
        $channelname = Model_Nav::get_channel_name_mobile($this->_typeid);
        $this->assign('typeid', $this->_typeid);
        $this->assign('channelname', $channelname);

    }

    /**
     * 首页
     */
    public function action_index()
    {
        $seoinfo = Model_Nav::get_channel_seo_mobile($this->_typeid);
        $this->assign('seoinfo', $seoinfo);
        $this->display('destination/index','dest_boot');
        //缓存内容
        $content = $this->response->body();
        Common::cache('set', $this->_cache_key, $content);
    }

    public function action_main()
    {
        //参数处理
        $destpy = Common::remove_xss($this->request->param('pinyin'));
        $destinfo = Model_Destinations::get_dest_bypinyin($destpy);
        //获取seo信息
        $seo = Model_Destinations::search_seo($destpy, 0);
        $this->assign('seoinfo', $seo);
        $this->assign('destinfo', $destinfo);
        if (!empty($destinfo))
        {
            $this->display('destination/main','dest_index');
        } else
        {
            $this->_jump_404();
        }
        //缓存内容
        $content = $this->response->body();
        Common::cache('set', $this->_cache_key, $content);
    }

    /**
     * 子站显示
     */
    public function action_sub_station()
    {

        $param = $this->request->param();
        //目的地检测
        $dest=Model_Destinations::get_dest_bypinyin($param['pinyin']);
        if (empty($dest) || Model_Model::exsits_model($param['model']))
        {
            $this->_jump_404();
        }
        //获取内容
        $html = file_get_contents($this->host_url . "/{$param['model']}/show_{$param['aid']}.html?webid={$dest['id']}");
        if (empty($html))
        {
            $this->_jump_404();
        }
        $this->response->body($html);

    }

    /**
     * 跳转404页面
     */
    private function _jump_404()
    {
        $url = $this->host_url . '/404';
        header("Location:{$url}");
        exit;
    }

}