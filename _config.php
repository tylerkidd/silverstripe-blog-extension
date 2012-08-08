<?php

if(class_exists('BlogTree') && class_exists('BlogHolder_Extension')) Object::add_extension('BlogHolder','BlogHolder_Extension');
if(class_exists('BlogEntry') && class_exists('BlogEntry_Extension')) Object::add_extension('BlogEntry','BlogEntry_Extension');
