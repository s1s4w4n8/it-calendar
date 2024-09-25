<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success" role="alert">
        <?php if ($_GET['success'] === 'updated'): ?>
            Event updated successfully!
        <?php elseif ($_GET['success'] === 'deleted'): ?>
            Event deleted successfully!
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php
include 'db.php';

$stmt = $pdo->query("SELECT * FROM events");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="npm/fullcalendar@5.10.1/main.min.css" />
    <!-- FullCalendar JS -->
    <script src="npm/fullcalendar@5.10.1/main.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- jQuery (for Bootstrap's modal) -->
    <script src="jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS (for modal) -->
    <script src="bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <title>IT Calendar</title>
    <style>
        #calendar {
            max-width: 900px;
            margin: 40px auto;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 align="center">IT Calendar</h2>
    <div id="calendar"></div>
    
</div>

<!-- Bootstrap Modal for Event Details -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4 id="modalTitle"></h4>
        <p><strong>Start:</strong> <span id="modalStart"></span></p>
        <p><strong>End:</strong> <span id="modalEnd"></span></p>
        <p><strong>Description:</strong> <span id="modalDescription"></span></p>
      </div>
      <div class="modal-footer">
        <!-- New Edit and Delete buttons -->
        <button hidden="hidden" type="button" class="btn btn-primary" id="editEventButton"></button>
        <button hidden="hidden" type="button" class="btn btn-danger" id="deleteEventButton"></button>
        <button hidden="hidden" type="button" class="btn btn-secondary" data-dismiss="modal"></button>
      </div>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var events = [
        <?php foreach ($events as $event): ?>
            {
                id: '<?php echo $event['id']; ?>',
                title: '<?php echo htmlspecialchars($event['title']); ?>',
                start: '<?php echo $event['start_datetime']; ?>',
                end: '<?php echo $event['end_datetime']; ?>',
                description: '<?php echo htmlspecialchars($event['description']); ?>'
            },
        <?php endforeach; ?>
    ];

    var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    events: events,
    
    // Hide event time by setting eventTimeFormat to false
    eventTimeFormat: { 
        hour: 'numeric', 
        minute: '2-digit', 
        omitZeroMinute: true, 
        meridiem: 'short', 
        hour12: false 
    }, 
    displayEventTime: false, // Don't display time
    
    eventDidMount: function(info) {
        // Force single-day events to display as full block (no dot)
        info.el.style.display = 'block';
        info.el.style.backgroundColor = '#3788d8'; // Consistent background color
        info.el.style.color = '#fff'; // White text for visibility

        // Custom HTML for event title with normal font weight
        info.el.querySelector('.fc-event-title').style.fontWeight = 'normal';
        info.el.querySelector('.fc-event-title').innerHTML = info.event.title;
    },
    
    eventClick: function(info) {
    // Set event details in the modal
    document.getElementById('modalTitle').innerHTML = info.event.title;
    document.getElementById('modalStart').innerText = info.event.start.toLocaleString();
    document.getElementById('modalEnd').innerText = info.event.end ? info.event.end.toLocaleString() : 'N/A';
    document.getElementById('modalDescription').innerHTML = info.event.extendedProps.description || 'No description';
    
    // Assign the event ID to the Edit and Delete buttons
    document.getElementById('editEventButton').setAttribute('data-event-id', info.event.id);
    document.getElementById('deleteEventButton').setAttribute('data-event-id', info.event.id);

    // Show the modal
    $('#eventModal').modal('show');
}

});

calendar.render();

    // Handle Edit button click
    document.getElementById('editEventButton').addEventListener('click', function() {
        var eventId = this.getAttribute('data-event-id');
        window.location.href = 'edit_event.php?id=' + eventId;
    });

    // Handle Delete button click
    document.getElementById('deleteEventButton').addEventListener('click', function() {
        var eventId = this.getAttribute('data-event-id');
        if (confirm('Are you sure you want to delete this event?')) {
            window.location.href = 'delete_event.php?id=' + eventId;
        }
    });
});

</script>
</body>
</html>
