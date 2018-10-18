<h1>Выполнение задания</h1>
<h3>PHP 5.3 и выше, ООП</h3>
<p>Приложение для выгрузки акций из csv файла в базу данных и немного заданий.</p>
<p>Склонируйте проект к себе на ПК, и произведите настройки, которые ниже.</p>
<h3><a id="Installation_5"></a>Installation</h3>
<p>Чтобы приступить к работе, произведите следующие действия</p>
<ul>
<li>Установите локальный веб-сервер с поддержкой PHP 5.3 и выше;</li>
<li>Установите composer;</li>
<li>Установите git;</li>
<li>Выполните инструкции по клонироавнию ниже.</li>
</ul>
<pre><code class="language-sh">
$ cd my_projects/
$ git <span class="hljs-built_in">clone</span> https://github.com/valanhol/choco.git
$ composer update
</code></pre>
<ul>
<li>Произведите настройку ./config/db_config.php используя db_config.example.php</li>
<pre><code class="language-sh">
$ cd config/
$ cp db_config.example.php db_config.php
</code></pre>
</ul>
