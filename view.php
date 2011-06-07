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

<div class="event" itemscope itemtype ="http://schema.org/Event">
    <!-- Event Name -->
    <div class="name" itemprop="name"><?php echo $event['name']; ?></div>

    <div class="col-abc">
        <div class="col-a">
            <span>Location:</span>
        </div>
        <div class="col-b">
            <div itemprop="location" itemscope itemtype="http://schema.org/PostalAddress">
                <!-- Location Name -->
                <div itemprop="name"><?php echo $event['location']['name']; ?></div>
                <!-- Location Address -->
                <div itemprop="streetAddress"><?php echo $event['location']['address']; ?></div>
            </div>
        </div>
        <div class="col-c">
            <time itemprop="startDate" datetime="<?php echo date('c', $event['date']['timestamp']) ?>">
                <span class="date">Date:</span><?php echo date('M, j, Y', $event['date']['timestamp']) ?><br/>
                <span class="time">Time:</span><?php echo date('g:i a T'); ?>
            </time>
        </div>
    </div>
    <div class="icl"></div>
</div>
