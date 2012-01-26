<?
    if (!empty($TPL['TABLEFORM']))
    {
        $first = true; $i_b_in_m = 0; $i_b_in_sp = 0; $cur_sp = ''; $cur_m = '';
        foreach ($TPL['TABLEFORM'] as $b)
        {
            if ($b['mid'] != $cur_m)
            {
                // fill numbers of bids in messure title ($i_b_in_m)
                $me_id = str_replace(".", "a", $cur_m);
                ?> <script> if ('i_b_in_m_<?=$me_id?>' != 'i_b_in_m_') {dojo.byId('i_b_in_m_<?=$me_id?>').innerHTML = <?=$i_b_in_m?>}</script> <?
                $cur_m = $b['mid'];
                $i_b_in_m = 0;
                if (!$first)
                { ?>
                    </table><br>
                    <input type="submit" value="Распечатать протокол вскрытия заявок"><br>
                    <input type="submit" value="Распечатать протокол соответствия заявок"><br>
                    <input type="submit" value="Распечатать протокол оценки заявок"><br><br /> <?
                }
                if ($b['sid'] != $cur_sp)
                {
                    // fill numbers of bids in subprogram title ($i_b_in_sp)
                    ?> <script> if ('i_b_in_sp_<?=$cur_sp?>' != 'i_b_in_sp_') {dojo.byId('i_b_in_sp_<?=$cur_sp?>').innerHTML = <?=$i_b_in_sp?>}</script> <?
                    $cur_sp = $b['sid'];
                    $i_b_in_sp = 0; ?>
                    <h4>Подпрограмма "<?=$b['stitle']; ?>"</h4>
                    Всего найдено <b><span id="i_b_in_sp_<?=$b['sid']?>"></span></b> заявок<br /><br /> <?
                } ?>
                <h5>Мероприятие "<?=$b['mtitle']; ?>"</h5> <?
                $me_id = str_replace(".", "a", $cur_m); ?>
                Всего найдено <b><span id="i_b_in_m_<?=$me_id?>"></span></b> заявок&nbsp;&nbsp;&nbsp;&nbsp;
                Дата окончания сбора тематики: <b><?=$b['finish_acquisition']?></b>&nbsp;&nbsp;&nbsp;&nbsp;
                Дата подведения итогов: <b><?=$b['summing_up_date']?></b><br>

                <table class="table">
                  <tr>
                    <th>№ п/п </th>
                    <th>Подпрограмма</th>
                    <th>Мероприятие</th>
                    <th>Департамент заказчик</th>
                    <th>Этап реализации</th>
                    <th>Шифр заявки</th>
                    <th>Предложенная тема работ</th>
                    <th>Предложенная сумма работ, руб.</th>
                    <th>В том числе по годам</th>
                    <th>Участник</th>
                    <th>Дата создания заявки</th>
                    <th>Дата подачи заявки в эл виде</th>
                    <th>Дата поступления заявки в бум. виде</th>
                    <th>Соответствие  заявки требованиям заказчика</th>
                    <th>Рейтинг заявки-эксперты, балл</th>
                    <th>Итоговый райтинг - Протокол НКС, балл </th>
                    <th>Победитель</th>
                    <th>Примечания</th>
                  </tr> <?
                $first = false;
            }
            $i_b_in_m++;
            $i_b_in_sp++; ?>
            <tr>
              <td><?=$b['bid']//$i_b_in_m?></td>
              <td><?=$b['stitle']; ?></td>
              <td><?=$b['mtitle']; ?></td>
              <td><?=$b['dtitle']; ?></td>
              <td><?=$b['step_name']; ?></td>
              <td><?=$b['start_realization']?>-<?=$b['mid']?>-<?=$b['bid']?></td>
              <td><?=$b['work_topic']?></td>
              <td><?=$b['price_works_actual']?></td>
              <td style="margin:0">
                  <table cellpadding="0" cellspacing="0" width="100%">
                      <? foreach ($TPL['YEARSMONEY'][$b['bid']] as $y=>$c) { ?>
                      <tr><td><?=$y?></td>
                      <td><?=$c?></td></tr> <? } ?>
                  </table>
              </td>
              <td><?=$b['applicant']?></td>
              <td><?=$b['date_create_bid']?></td>
              <td><?=$b['datetime_electron_bid_receiving']?></td>
              <td><?=$b['datetime_paper_bid_receiving']?></td>
              <td><?=$b['matches']?></td>
              <td><?=$b['rating_experts']?></td>
              <td><?=$b['rating_protocol_NKS']?></td>
              <td><?=$b['winner']?></td>
              <td><?=$b['cnt_comment']?></td>
            </tr> <?
        }
        echo "</table>";
        // fill numbers of bids in messure title ($i_b_in_m)
        $me_id = str_replace(".", "a", $cur_m);
        ?> <script> if ('i_b_in_m_<?=$me_id?>' != 'i_b_in_m_') {dojo.byId('i_b_in_m_<?=$me_id?>').innerHTML = <?=$i_b_in_m?>}</script> <?
        // fill numbers of bids in subprogram title ($i_b_in_sp)
        ?> <script> if ('i_b_in_sp_<?=$cur_sp?>' != 'i_b_in_sp_') {dojo.byId('i_b_in_sp_<?=$cur_sp?>').innerHTML = <?=$i_b_in_sp?>}</script> <?
    } else
    {
        echo "Объявленных конкурсов нет";
    } ?>
