<?
    if (!empty($TPL['TABLEFORM']))
    { ?>
        <table class="table">
          <tr>
            <th>№ п/п </th>
            <th>Подпрограмма</th>
            <th>Мероприятие</th>
            <th>Департамент заказчик</th>
            <th>Этап реализации</th>
            <th>Cтатус заявки</th>
            <th>Дата регистрации заявки в эл. виде</th>
            <th>Дата рагистрации заявки в бум. виде</th>
          </tr>
        <? $i=0; $cur_mes = -1; $old_mes = '';
          foreach ($TPL['TABLEFORM'] as $mes)
          {
                if ($mes['mid'] != $cur_mes)
                {
                    $i++;
                    if ($i>1)
                    { ?>
                                    <tr><td><a href="/forms/createbid/<?=$old_mes['nid']?>/<?=$old_mes['mid']?>">создать</a></td></tr>
                                </table>
                            </td>
                            <td><?=$old_mes['datetime_electron_bid_receiving']?></td>
                            <td><?=$old_mes['datetime_paper_bid_receiving']?></td>
                        </tr> <?
                    } ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=$mes['stitle']?></td>
                            <td><?=$mes['mid']?> <?=$mes['mtitle']?></td>
                            <td><?=$mes['dtitle']?></td>
                            <td><?=$mes['step_name']?></td>
                            <td>
                                <table width="100%" cellpadding=0 cellspacing=0 class="noborder"> <?
                                if (!empty($mes['bid']))
                                { ?>
                                    <tr><td><? if (!empty($mes['datetime_electron_bid_receiving'])) echo "подана"; else echo "не подана";?><br><nobr>
                                    <a href="/forms/bid/<?=$mes['bid']?>"><?=$mes['start_realization']?>-<?=$mes['mid']?>-<?=$mes['bid']?></a></nobr></td></tr> <?
                                }
                    $cur_mes = $mes['mid'];
                } else
                { ?>
                                    <tr><td><? if (!empty($mes['datetime_electron_bid_receiving'])) echo "подана"; else echo "не подана";?><br><nobr>
                                    <a href="/forms/bid/<?=$mes['bid']?>"><?=$mes['start_realization']?>-<?=$mes['mid']?>-<?=$mes['bid']?></a></nobr></td></tr> <?
                }
                $old_mes = $mes; ?>
         <? } ?>
                                    <tr><td><a href="/forms/createbid/<?=$mes['nid']?>/<?=$mes['mid']?>">создать</a></td></tr>
                                </table>
                            </td>
                            <td><?=$mes['datetime_electron_bid_receiving']?></td>
                            <td><?=$mes['datetime_paper_bid_receiving']?></td>
                        </tr>
           <tr>
           </tr>
        </table>  <?
    } else
    {
        echo "Объявленных конкурсов нет";
    } ?>
