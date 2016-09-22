<?php

/**
 * bingo
 */
class Bingo
{

    // {{{ consts
    //ビンゴ行列
    const ROW = 3;
    const COL = 3;

    const STATUS_DEFAULT    = 0;    // デフォルト
    const STATUS_REACH      = 1;    // リーチ
    const STATUS_BINGO      = 2;    // ビンゴ

    const SLASH_LEFT        = 0;   //左上から斜め
    const SLASH_RIGHT       = 1;   //右上から斜め
    // }}}

    // {{{ public function getBingoResult($clear_mass_ids)
    /**
     *
     * @param type $clear_mass_ids
     * @return type
     */
    public function getBingoResult($clear_mass_ids)
    {
        $row = $this->getBingoByRow($clear_mass_ids);
        $col = $this->getBingoByCol($clear_mass_ids);
        $slash = $this->getBingoBySlash($clear_mass_ids);
        return array($row,$col,$slash);
    }
    // }}}

    // {{{ public function getBingoNum($bingo_result)
    /**
     * ビンゴ結果からビンゴ数を得る
     * @param type $bingo_result
     */
    public function getBingoNum($bingo_result)
    {
        $count = 0;
        foreach ( $bingo_result as $list ) {
            foreach ( $list as $row ) {
                if ( $row == self::STATUS_BINGO ) {
                    $count ++;
                }
            }
        }

        return $count;
    }
    // }}}

    // {{{ public function getBingoByRow($clear_no_list)
    /**
     * ビンゴしている列番号の番号を返す
     */
    public function getBingoByRow($clear_mass_list)
    {
        $clear_row_list = array();
        for($i=1;$i<=self::ROW;$i++)
        {
            $count = 0;
            for($j=1;$j<=self::COL;$j++)
            {
                // チェックするマス番号を算出
                /* ( ( 行番号 - 1 ) * 列数 ) + 列番号 */
                $hit_no = ( ($i - 1) * self::COL ) + $j;
                if (in_array($hit_no, $clear_mass_list)) {
                    $count ++;
                }
                if ( $count == self::COL - 1 ) { $status = self::STATUS_REACH; }
                if ( $count >= self::COL ) { $status = self::STATUS_BINGO; }
                else { $status = self::STATUS_DEFAULT; }
                $clear_row_list[$i] = $status;
            }
        }

        return $clear_row_list;
    }
    // }}}

    // {{{ public function getBingoByCol($clear_no_list)
    /**
     * ビンゴしている行番号の番号を返す
     */
    public function getBingoByCol($clear_mass_list)
    {
        $clear_col_list = array();
        for($i=1;$i<=self::ROW;$i++)
        {
            $count = 0;
            for($j=1;$j<=self::COL;$j++)
            {
                // チェックするマス番号を算出
                /* ( (列番号 - 1) * 行数 ) + 行番号 */
                $hit_no = ( ($j - 1) * self::ROW ) + $i;
                if (in_array($hit_no, $clear_mass_list)) {
                    $count ++;
                }
                if ( $count == self::ROW - 1 ) { $status = self::STATUS_REACH; }
                else if ( $count >= self::ROW ) { $status = self::STATUS_BINGO; }
                else { $status = self::STATUS_DEFAULT; }
                $clear_col_list[$i] = $status;
            }
        }
        return $clear_col_list;
    }
    // }}}

    // {{{ public function getBingoBySlash($clear_no_list)
    /**
     * ビンゴしている斜め番号の番号(左上から:0 右上から:1)を返す
     */
    public function getBingoBySlash($clear_mass_list)
    {
        // 行・列数が一致していないと斜めビンゴは発生しない
        if ( self::ROW != self::COL ) return array();

        $clear_slash_list = array();

        //左上 -> 右上からの順で判定
        for ($i=0;$i<2;$i++)
        {
            $key = ( $i == self::SLASH_LEFT ) ? self::SLASH_LEFT : self::SLASH_RIGHT;
            $count = 0;

            for ($j=0;$j<self::COL;$j++) {

                // 基礎値
                $base_no = ( $i == self::SLASH_LEFT ) ? 1 : self::COL;

                // 行ごとの差分
                //  左上から: 列数 + 1
                //  右上から: 列数 - 1
                $delta = self::COL + ( 1 - ($i * 2) );

                // チェックするマス番号を算出
                $hit_no = $base_no + $delta  * $j;

                if (in_array($hit_no, $clear_mass_list)) {
                    $count ++;
                }

                //結果判定
                if ( $count == self::COL - 1 ) { $status = self::STATUS_REACH; }
                else if ( $count >= self::COL ) { $status = self::STATUS_BINGO; }
                else { $status = self::STATUS_DEFAULT; }

                $clear_slash_list[$key] = $status;
            }
        }
        return $clear_slash_list;
    }
    // }}}
}
