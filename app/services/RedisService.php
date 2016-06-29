<?php
/**
 * Redis 操作类
 *
 *
 * @author ron_chen<ron_chen@hotmail.com>
 * @copyright ron_chen<ron_chen@hotmail.com>
 * @link http://www.ronchen.me/
 */
class RedisService{

	private $redis;

	public function __construct($redis){
		$this->redis = $redis;
	}

	/**
     * 设置值
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param int $timeOut 时间
     */
    public function set($key, $value, $timeOut = 0)
    {
        $value = json_encode($value, true);
        $retRes = $this->redis->set($key, $value);
        if ($timeOut > 0) {
            $this->redis->setTimeout($key, $timeOut);
        }

        return $retRes;
    }

    /**
     * 通过KEY获取数据
     * @param string $key KEY名称
     */
    public function get($key)
    {
        $result = $this->redis->get($key);

        return json_decode($result, true);
    }

    /**
     * 删除一条数据
     * @param string $key KEY名称
     */
    public function delete($key)
    {
        return $this->redis->delete($key);
    }

    /**
     * 清空数据
     */
    public function flushAll()
    {
        return $this->redis->flushAll();
    }

    /**
     * 数据入队列
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param bool $right 是否从右边开始入
     */
    public function push($key, $value, $right = true)
    {
        $value = json_encode($value);

        return $right ? $this->redis->rPush($key, $value) : $this->redis->lPush($key, $value);
    }

    /**
     * 数据出队列
     * @param string $key KEY名称
     * @param bool $left 是否从左边开始出数据
     */
    public function pop($key, $left = true)
    {
        $val = $left ? $this->redis->lPop($key) : $this->redis->rPop($key);

        return json_decode($val);
    }

    /**
     * 数据自增
     * @param string $key KEY名称
     */
    public function increment($key)
    {
        return $this->redis->incr($key);
    }

    /**
     * 数据自减
     * @param string $key KEY名称
     */
    public function decrement($key)
    {
        return $this->redis->decr($key);
    }

    /**
     * key是否存在，存在返回ture
     * @param string $key KEY名称
     */
    public function exists($key)
    {
        return $this->redis->exists($key);
    }
}