<div>
    Dear customer,
    {{ $eventName }} was updated successfully. <br />
    May the change bring wealth.
</div>


<div>
    <p>
        Dear customer,
        {{ $eventName }} was updated successfully. <br />
    </p>
    <p>
        <a href="{{ url('/event/' . $eventId) }}"> Feel free to visit</a>
    </p>
    <p>
        May the change bring wealth.<br />
    </p>
</div>
