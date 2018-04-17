<?php

/*
 * This file is part of the docodeit/wechat.
 *
 * (c) docodeit <lqbzdyj@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace JinWeChat\OfficialAccount\Appmsg;

use JinWeChat\Kernel\BaseClient;

class Client extends BaseClient
{
    const URL = 'cgi-bin/appmsg';
    const EDIT_URL = 'cgi-bin/operate_appmsg';

    /**
     * 图文素材列表.
     *
     * @param $begin
     * @param $count
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     */
    public function list($begin = 0, $count = 10)
    {
        $query = [
            'type' => '10',
            'action' => 'list',
            'begin' => $begin,
            'count' => $count,
            'f' => 'json',
            'lang' => 'zh_CN',
            'ajax' => '1',
            'random' => $this->getMillisecond(),
        ];

        return $this->httpGet(self::URL, $query);
    }

    /**
     * 素材列表
     * @param int $begin
     * @param int $count
     * @param int $type
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function appmsgList($begin = 0, $count = 10, $type = 10)
    {
        $query = [
            'begin' => $begin,
            'count' => $count,
            't' => 'media/appmsg_list',
            'type' => $type,
            'action' => 'list_card',
            'lang' => 'zh_CN',
            'token' => $this->token,
            'f' => 'json',
        ];
        return $this->httpGet(self::URL, $query);
    }

    /**
     * 获取永久链接
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getForeverUrl()
    {
        $query = [
            'lang' => 'zh_CN',
            'token' => $this->token,
            'f' => 'json',
            'ajax' => '1',
            'random' => (int)$this->getMillisecond(),
            'action' => 'list_ex',
            'begin' => '0',
            'count' => '999',
            'query' => '',
            'link' => '1',
            'scene' => '1',
        ];
        return $this->httpGet(self::URL, $query);
    }

    /**
     * 获取预览链接
     * @param $appmsgid
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getPreviewUrl($appmsgid)
    {
        $query = [
            'action' => 'get_temp_url',
            'lang' => 'zh_CN',
            'ajax' => '1',
            'random' => $this->getMillisecond(),
            'token' => $this->token,
            'appmsgid' => $appmsgid,
            'itemidx' => '1',
        ];
        return $this->httpGet(self::URL, $query);
    }

    public function saveNews($count, $type = 10)
    {
        $query = [
            't' => 'ajax-response',
            'sub' => 'update',
            'type' => $type,
            'token' => $this->token,
            'lang' => 'zh_CN',
        ];
        for ($i = 0; $i <= $count; $i++) {
            $list[] = [
                'can_reward' . $i => '0',
                'title' . $i => '',
                'author' . $i => '1',
                'fileid' . $i => '',
                'digest' . $i => '1',
                'content' . $i => '<p>1</p>',
                'sourceurl' . $i => '',
                'need_open_comment' . $i => '1',
                'only_fans_can_comment' . $i => '0',
                'cdn_url' . $i => '',
                'cdn_url_back' . $i => '',
                'music_id' . $i => '',
                'video_id' . $i => '',
                'voteid' . $i => '',
                'voteismlt' . $i => '',
                'supervoteid' . $i => '',
                'cardid' . $i => '',
                'cardquantity' . $i => '',
                'cardlimit' . $i => '',
                'vid_type' . $i => '',
                'reward_money' . $i => '0',
                'reward_wording' . $i => '',
                'show_cover_pic' . $i => '0',
                'shortvideofileid' . $i => '',
                'copyright_type' . $i => '0',
                'releasefirst' . $i => '',
                'platform' . $i => '',
                'reprint_permit_type' . $i => '',
                'original_article_type' . $i => '',
                'ori_white_list' . $i => '',
                'free_content' . $i => '',
                'fee' . $i => '0',
                'ad_id' . $i => '',
                'guide_words' . $i => '',
                'is_share_copyright' . $i => '0',
                'share_copyright_url' . $i => '',
                'share_page_type' . $i => '0',
                'share_imageinfo' . $i => '{"list":[]}',
                'share_video_id' . $i => '',
                'share_voice_id' . $i => '',
            ];
        }
        $data = [
            'token' => $this->token,
            'lang' => 'zh_CN',
            'f' => 'json',
            'ajax' => '1',
            'random' => $this->getMillisecond(),
            'AppMsgId' => '',
            'count' => $count,
            'data_seq' => '0',
            'operate_from' => 'Chrome'
        ];
        $this->httpPost(self::EDIT_URL, $data, $query);
    }
}
