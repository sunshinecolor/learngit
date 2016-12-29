<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/28 0028
 * Time: 9:27
 */
/*include_once ('config/config.php');
include_once (INCLUDE_PATH."/common.php");
include_once ('php/postGreSql.php');
$res = new postGreSql($db);*/
/*$sql = "SELECT sa.name, sa.cur_num, sb.in_count*sa.cost_price AS cost,sd.all_money,sd.all_sales FROM tb_shop sa
                LEFT JOIN (SELECT sc.prod_id, SUM(sc.num) AS in_count FROM tb_prod_recorder sc WHERE (sc.record_time<='$etime' AND sc.record_time>='$btime' AND
                sc.access_type = 1) GROUP BY sc.prod_id) sb ON sb.prod_id= sa.goods_id
                LEFT JOIN (SELECT se.goods_id,SUM(so.`order_money`) AS all_money,SUM(so.`order_sales`) AS all_sales FROM `tb_orderdata` se
                LEFT JOIN tb_shop_order so ON so.`order_id`=se.`order_id`
                WHERE(se.order_id IN
                (SELECT order_id FROM tb_shop_order sf WHERE (sf.add_time<='$etime' AND sf.add_time>='$btime' AND sf.order_state != 3)))GROUP BY se.goods_id) sd
                ON sd.goods_id = sa.goods_id limit $page->start,$page->show";*/
/*$sql = 'SELECT g.user_id,pa.* from (SELECT uu.group_id,u.user_id from tb_users u INNER JOIN tb_users_ugrps uu ON u.user_id = uu.user_id WHERE u.user_id = 1) g
        INNER JOIN tb_project_authority pa ON pa.object_id = g.group_id AND pa.object_type = 0 WHERE pa.project_id = 3';
$result = $res->query($sql);
var_dump($result);*/
var_dump($_SERVER);
echo base64_encode(sha1('123456',true));


