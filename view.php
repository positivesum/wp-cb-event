<?php
/*
Array
(
    [name] => Valera Satsura Birthday
    [date] => Array
        (
            [mm] => 01
            [jj] => 11
            [aa] => 1987
            [hh] => 11
            [mn] => 12
            [tz] => est
            [timestamp] => 537361920
        )

    [location] => Array
        (
            [name] => Home
            [address] => Kolesnika 9/40
            [city] => Hoiniki
            [province] =>
            [state] => Gomel
            [country] => Belarus
        )

)
*/
?>
<div class="event" itemscope itemtype="http://schema.org/Event">
    <h4 itemprop="name"><?php echo $event['name']; ?></h4>
    <table>
        <tbody>
            <tr>
                <td>Location:</td>
                <td>
                    <div itemprop="location" itemscope itemtype="http://schema.org/PostalAddress">
                        <em itemprop="name"><?php echo $event['location']['name']; ?></em><br/>
                        <span itemprop="streetAddress"><?php echo $event['location']['address']; ?></span><br/>
                        <span itemprop="addressLocality"><?php echo $event['location']['city']; ?></span><br/>
                    </div>
                </td>
                <td>
                    <time itemprop="startDate" datetime="<?php echo date('c', $event['date']['timestamp']) ?>">
                        Date:&nbsp;<?php echo date('M, j, Y', $event['date']['timestamp']) ?><br/>
                        Time:&nbsp;<?php echo date('g:i a', $event['date']['timestamp']).'&nbsp;'.strtoupper($event['date']['tz']); ?>
                    </time>
                </td>
            </tr>
        <tr>
            <td colspan="3">
               <br/>
               <a href="<?php echo $event['link']; ?>"><i class="ico"></i>Add to Calendar</a>
            </td>
        </tr>
        </tbody>
    </table>
</div>

