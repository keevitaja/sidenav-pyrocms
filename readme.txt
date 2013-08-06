Sidenav - PyroCMS module for displaying menu in sidebar

Demo:
http://sidenav.vebia.ee/

For details see pyrocms forum topic:
https://forum.pyrocms.com/discussion/24697/sidenav-module-for-displaying-menu-in-sidebar

default layout used in demo:

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />

    <title>Sidenav demo</title>

    <style type="text/css">
        body { margin: 0; font: 14px/24px Arial, sans-serif; }
        div { width: 960px; margin: 0 auto; }
        header { padding: 0 20px; background-color: #f5f5f5; margin-bottom: 30px; }
        header ul, header li { margin: 0; padding: 0; list-style: none; }
        header ul { padding: 20px 0; }
        header li { display: inline; margin-right: 12px; }
        .sidebar aside { float: left; width: 280px; background-color: #f5f5f5; }
        .sidebar aside li.sidenav-container { list-style: none; }
        section { background-color: #f5f5f5; padding: 20px; }
        .sidebar section { float: right; width: 580px; }
    </style>

</head>

<body class="{{ sidenav:css_class }}">

<div>
    <header>
        <ul>{{ navigation:links group="header" }}</ul>
    </header>

    {{ if sidenav:has_links }}
        <aside>
            <ul>
                {{ sidenav:links }}
            </ul>
        </aside>
    {{ endif }}

    <section>
        {{ template:body }}
    </section>
</div>

</body>
</html>

