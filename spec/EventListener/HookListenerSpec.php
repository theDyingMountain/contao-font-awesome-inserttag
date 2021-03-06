<?php

/**
 * @package    contao-font-awesome-inserttag
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015-2017 netzmacht David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace spec\Netzmacht\Contao\FontAwesomeInsertTag\EventListener;

use Netzmacht\Contao\FontAwesomeInsertTag\EventListener\HookListener;
use PhpSpec\ObjectBehavior;

/**
 * Class HookListenerSpec
 *
 * @package spec\Netzmacht\Contao\FontAwesomeInsertTag
 * @mixin HookListener
 */
class HookListenerSpec extends ObjectBehavior
{
    private $iconTemplate  = '<i class="%s" aria-hidden="true"></i>';
    private $stackTemplate = '<span class="fa-stack%s">%s%s</span>';
    private $defaultStyle  = 'fa';

    function let()
    {
        $this->beConstructedWith($this->iconTemplate, $this->stackTemplate, $this->defaultStyle);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(HookListener::class);
    }

    function it_parses_icon_insert_tag_with_simple_icon()
    {
        $this->shouldReplaceInsertTag(
            '%s::plus',
            '<i class="%s fa-plus" aria-hidden="true"></i>'
        );
    }

    function it_parses_icon_insert_tag_with_extra_fa_classes()
    {
        $this->shouldReplaceInsertTag(
            '%s::plus 2x',
            '<i class="%s fa-plus fa-2x" aria-hidden="true"></i>'
        );
    }

    function it_parses_icon_insert_tag_with_extra_classes()
    {
        $this->shouldReplaceInsertTag(
            '%s::plus:pull-left',
            '<i class="%s fa-plus pull-left" aria-hidden="true"></i>'
        );
    }

    function it_parses_icon_insert_tag_with_extra_classes_using_double_colon()
    {
        $this->shouldReplaceInsertTag(
            '%s::plus::pull-left',
            '<i class="%s fa-plus pull-left" aria-hidden="true"></i>'
        );
    }

    function it_parses_icon_stack_insert_tag_with_simple_icons()
    {
        $this
            ->onReplaceInsertTags('fa-stack::square::plus')
            ->shouldReturn('<span class="fa-stack"><i class="fa fa-square" aria-hidden="true"></i><i class="fa fa-plus" aria-hidden="true"></i></span>');
    }

    function it_parses_icon_stack_insert_tag_with_fa_classes()
    {
        $this
            ->onReplaceInsertTags('fa-stack::square 2x::plus 1x')
            ->shouldReturn('<span class="fa-stack"><i class="fa fa-square fa-2x" aria-hidden="true"></i><i class="fa fa-plus fa-1x" aria-hidden="true"></i></span>');
    }

    function it_parses_icon_stack_insert_tag_with_extra_classes()
    {
        $this
            ->onReplaceInsertTags('fa-stack::square 2x:pull-left::plus 1x:pull-right')
            ->shouldReturn('<span class="fa-stack"><i class="fa fa-square fa-2x pull-left" aria-hidden="true"></i><i class="fa fa-plus fa-1x pull-right" aria-hidden="true"></i></span>');
    }

    function it_parses_icon_stack_insert_tag_with_support_for_stack_classes()
    {
        $this
            ->onReplaceInsertTags('fa-stack::square::plus::lg')
            ->shouldReturn('<span class="fa-stack fa-lg"><i class="fa fa-square" aria-hidden="true"></i><i class="fa fa-plus" aria-hidden="true"></i></span>');
    }

    function it_parses_icon_stack_insert_tag_with_support_for_stack_extra_classes()
    {
        $this
            ->onReplaceInsertTags('fa-stack::square::plus::lg:extra')
            ->shouldReturn('<span class="fa-stack fa-lg extra"><i class="fa fa-square" aria-hidden="true"></i><i class="fa fa-plus" aria-hidden="true"></i></span>');
    }

    private function shouldReplaceInsertTag($insertTag, $result)
    {
        foreach (['fa', 'far', 'fas', 'fal', 'fab'] as $style) {
            $this
                ->onReplaceInsertTags(sprintf($insertTag, $style))
                ->shouldReturn(sprintf($result, $style));
        }
    }
}
