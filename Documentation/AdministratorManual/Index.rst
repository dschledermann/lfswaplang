.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

.. role::   underline
.. role::   typoscript(code)
.. role::   ts(typoscript)
   :class:  typoscript
.. role::   php(code)

.. _admin-manual:

Administrator Manual
====================

Target group: **Administrators**

Installation
------------

There are no configuration parameters or setting.
Just install the extension, and you are ready to go.

Usage
-----

The interaction with the code is simple: TYPO3 cli interface.
The goal is simply to swap the default langauge with some defined language.
Usage is as follows:

.. code-block:: shell-script

   php typo3/cli_dispatch.phpsh swaplang 


In order for this command to do something useful, you should supply a page id and a language name.
The page id is just pages.uid for the top page in question.
The language name is a label that is exactly equal to sys\_language.title.

The code will then look like this:

.. code-block:: shell-script

   php typo3/cli_dispatch.phpsh swaplang 3862 Dansk

If the page in question is 3862 and the language to swap to default language is "Dansk",
then the command will print out a list of pages and content elements that will be swapped.
It also prints the headers and titles of the elements.

If you are satisfied with the list, you can supply the argument "really" at the end, to execute the swap.

.. code-block:: shell-script

   php typo3/cli_dispatch.phpsh swaplang 3862 Dansk really

Depending on the size of the pagetree this might take some time.
The process can be repeated, resulting in the languages being swapped back.
If you are swapping to languages which both are non-default, the default language must be used as holding place.
