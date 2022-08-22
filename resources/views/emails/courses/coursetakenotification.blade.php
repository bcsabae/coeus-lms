<style>
    body {
        font-family: "Arial", "Helvetica Neue", "sans-serif";
    }
</style>

<p>Hi {{$user->name}},</p>
<p>You have taken the course {{$course->title}}</p>
<p>To view the course, <a href="{{route('courses.show', ['course' => $course->slug])}}">click here</a> </p>

<br>

<p>Have a nice day!</p>

<br>

<hr>

<p>Good crafting,</p>
<p>Garry</p>
