<?php

use Nette\Forms\Form;
use Tracy\Debugger;
use Tracy\Dumper;
Debugger::enable();

function makeBootstrap4(Form $form): void
    {
        $renderer = $form->getRenderer();
        $renderer->wrappers['controls']['container'] = null;
        $renderer->wrappers['pair']['container'] = 'div class="form-group"';
        $renderer->wrappers['pair']['.error'] = 'has-danger';
        $renderer->wrappers['control']['container'] = null;
        $renderer->wrappers['label']['container'] = 'div class="form-label"';
        $renderer->wrappers['control']['description'] = 'span class=form-text';
        $renderer->wrappers['control']['errorcontainer'] = 'span class=form-control-feedback';
        $renderer->wrappers['control']['.error'] = 'is-invalid';
        
        foreach ($form->getControls() as $control) {
            $type = $control->getOption('type');
            if ($type === 'button') {
                $control->getControlPrototype()->addClass(empty($usedPrimary) ? 'btn btn-primary' : 'btn btn-secondary');
                $usedPrimary = true;
                
            } elseif (in_array($type, ['text', 'select'], true)) {
                $control->getControlPrototype()->addClass('form-control');
            } elseif ( in_array($type, ['textarea'], true) ) {
                $control->getControlPrototype()->addClass('form-control editor');
            } elseif ($type === 'file') {
                $control->getControlPrototype()->addClass('form-control file');
                
            } elseif (in_array($type, ['checkbox', 'radio'], true)) {
                if ($control instanceof Nette\Forms\Controls\Checkbox) {
                    $control->getLabelPrototype()->addClass('form-check-label');
                } else {
                    $control->getItemLabelPrototype()->addClass('form-check-label');
                }
                $control->getControlPrototype()->addClass('form-check-input');
                $control->getSeparatorPrototype()->setName('div')->addClass('form-check');
            }
        }
    }

function copyright($from = '2002', $text1 = '',  $text2 = 'Made with &hearts; by err404', $link = '', $text3 = 'Powered by NetteFramework') {
    $y = date('Y');
    $dates = $y > $from ? "{$from} - {$y}" : $from;
    
    if( empty($text1) ){
        $text1 = 'All rights reserved ';
    }
    if( empty($text3) ){
        $text3 = '&middot;';
    } else {
        $text3 = $text3 . ' &middot;';
    }
    if( empty($link) ){
        $copyright = sprintf( html_entity_decode('&copy; %1$s &middot; %2$s &middot; %3$s &middot; %4$s'), "{$dates}", $text1, $text2, $text3 );
    } else {
        $copyright = sprintf( html_entity_decode('<span>&copy;</span> %1$s %2$s &middot; %3$s%4$s%5$s %6$s'), "{$dates}", $text1, "<a href='{$link}' class='f-link' role='url' rel='bookmark' target='_blank'>", $text2, '</a>', $text3 );
    }
    
    return $copyright;
}

