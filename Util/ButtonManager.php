<?php
namespace Hillrange\Form\Util;

use Symfony\Component\Form\FormView;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ButtonManager
 * @package Hillrange\Form\Util
 */
class ButtonManager
{
    /**
     * @var array
     */
    private $buttons;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * ButtonExtension constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator, RouterInterface $router)
    {
        $this->translator = $translator;
        $this->router = $router;
        $buttons = <<<XXX
save:
    class: "fas fa-upload btn btn-success"
    type: submit
    title: 'form.save'
    style: 'float: right;'
    name: Save
    attr: ''
    prompt: ''
cancel:
    class: "far fa-times-circle btn btn-info"
    type: button
    title: 'form.cancel'
    style: 'float: right;'
    attr: ''
    prompt: ''
upload:
    class: "fas fa-cloud-upload-alt btn btn-success"
    type: submit
    title: 'form.upload'
    style: 'float: right;'
    attr: ''
    prompt: ''
add:
    class: "fas fa-plus-circle btn btn-info"
    type: button
    title: 'form.add'
    style: 'float: right;'
    attr: ''
    prompt: ''
edit:
    class: "fas fa-edit btn btn-info"
    type: button
    title: 'form.edit'
    style: 'float: right;'
    attr: ''
    prompt: ''
proceed:
    class: "far fa-hand-point-right btn btn-info"
    type: button
    title: 'form.proceed'
    style: 'float: right;'
    attr: ''
    prompt: ''
return:
    class: "far fa-hand-point-left btn btn-primary"
    type: button
    title: 'form.return'
    style: 'float: right;'
    attr: ''
    prompt: ''
delete:
    class: "fas fa-eraser btn btn-danger"
    type: button
    title: 'form.delete'
    style: 'float: right;'
    attr: ''
    prompt: ''
remove:
    class: "fas fa-eraser btn btn-warning collection-remove collection-action"
    type: button
    title: 'form.remove'
    style: 'float: right;'
    attr: ''
    prompt: ''
reset:
    class: "fas fa-sync btn btn-warning"
    type: reset
    title: 'form.reset'
    style: 'float: right;'
    name: Reset
    prompt: ''
    attr: ''
refresh:
    class: "fas fa-redo btn btn-warning"
    type: reset
    title: 'form.reset'
    style: 'float: right;'
    name: Refresh
    prompt: ''
    attr: ''
misc:
    class: ""
    type: button
    title: 'form.misc'
    style: 'float: right;'
    attr: ''
    prompt: ''
close:
    class: "far fa-times-circle btn btn-primary"
    type: button
    title: 'form.close'
    style: 'float: right;'
    attr: 'onclick="window.close();"'
    prompt: ''
duplicate:
    class: "fas fa-copy btn btn-primary"
    type: button
    title: 'form.duplicate'
    style: 'float: right;'
    attr: ''
    prompt: ''
up:
    class: "collection-up collection-action fas fa-arrow-up btn btn-light"
    type: button
    title: 'form.up'
    style: 'float: right;'
    attr: ''
    prompt: ''
down:
    class: "collection-down collection-action fas fa-arrow-down btn btn-light"
    type: button
    title: 'form.down'
    style: 'float: right;'
    attr: ''
    prompt: ''
on:
    class: "far fa-thumbs-up btn btn-success"
    type: button
    title: 'form.on'
    style: 'float: right;'
    attr: ''
    prompt: ''
off:
    class: "far fa-thumbs-down btn btn-danger"
    type: button
    title: 'form.off'
    style: 'float: right;'
    attr: ''
    prompt: ''
XXX;
        $this->buttons = Yaml::parse($buttons);
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function saveButton($details = [])
    {
        return $this->generateButton($this->buttons['save'], $details);
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function cancelButton($details = [])
    {
        return $this->generateButton($this->buttons['cancel'], $details);
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function uploadButton($details = [])
    {
        return $this->generateButton($this->buttons['upload'], $details);
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function addButton($details = [])
    {
        return $this->generateButton($this->buttons['add'], $details);
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function editButton($details = [])
    {
        return $this->generateButton($this->buttons['edit'], $details);
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function proceedButton($details = [])
    {
        return $this->generateButton($this->buttons['proceed'], $details);
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function returnButton($details = [])
    {
        if (! empty($details['returnTo']))
        {
            $details['windowOpen'] = ['route' => $details['returnTo']];
            unset($details['returnTo']);
        }
        return $this->generateButton($this->buttons['return'], $details);
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function deleteButton($details = [])
    {
        return $this->generateButton($this->buttons['delete'], $details);
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function removeButton($details = [])
    {
        return $this->generateCollectionButton($this->buttons['remove'], $details);
    }

    /**
     * miscButton
     *
     * @param array $details
     * @return string
     */
    public function miscButton($details = [])
    {
        return $this->generateButton($this->buttons['misc'], $details);
    }

    /**
     * refreshButton
     *
     * @param array $details
     * @return string
     */
    public function refreshButton($details = array())
    {
        return $this->generateButton($this->buttons['refresh'], $details);
    }

    /**
     * resetButton
     *
     * @param array $details
     * @return string
     */
    public function resetButton($details = array())
    {
        $details['mergeClass'] = ! empty($details['mergeClass']) ? $details['mergeClass'] . ' resetButton' : 'resetButton';
        return $this->generateButton($this->buttons['reset'], $details);
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function closeButton($details = array())
    {
        return $this->generateButton($this->buttons['close'], $details);
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function upButton($details = array())
    {
        return $this->generateCollectionButton($this->buttons['up'], $details);
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function downButton($details = array())
    {
        return $this->generateCollectionButton($this->buttons['down'], $details);
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function upDownButton(array $details = []): string
    {
        return $this->generateCollectionButton($this->buttons['down'], $details) . $this->generateCollectionButton($this->buttons['up'], $details);
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function toggleButton(array $details): string
    {
        $toggle = '
<span class="%class%" toggle_swap="%toggle_swap%" data-toggle="buttons" id="%id%_span" style="%style%" %callable%>
    <input type="hidden" value="%value%" id="%id%" name="%name%" />
    <span%icon%></span>
</span>
<label class="control_label" for="%id%">%label%
</label>';

        if (isset($details['form']))
        {
            $vars               = $details['form']->vars;

            $details['name']    = $vars['full_name'];

            $details['id']      = $vars['id'];
        }

        if (isset($details['icon'])) {
            if (is_array($details['icon'])) {
                $icon = '';
                foreach($details['icon'] as $name=>$value)
                    $icon .= $name . '="' . $value . '" ';
                $defaults['icon'] = ' ' . trim($icon);
            }
            unset($details['icon']);
        } else {
            $icon = '';
            if (preg_match('#^far | far | far$#', $details['class'], $matches) > 0) {
                $icon .= 'far ';
                $details['class'] = str_replace($matches, '', $details['class']);
            }

            if (preg_match('#^fas | fas | fas$#', $details['class'], $matches) > 0) {
                $icon .= 'fas ';
                $details['class'] = str_replace($matches, '', $details['class']);
            }

            if (preg_match('#^fal | fal | fal$#', $details['class'], $matches) > 0) {
                $icon .= 'fal ';
                $details['class'] = str_replace($matches, '', $details['class']);
            }

            if (preg_match('#^fab | fab | fab$#', $details['class'], $matches) > 0) {
                $icon .= 'fab ';
                $details['class'] = str_replace($matches, '', $details['class']);
            }

            if (preg_match('#fa-([\S][\w-]*)#', $details['class'], $matches) > 0) {
                $icon .= $matches[0] . ' ';
                $details['class'] = str_replace($matches, '', $details['class']);
            }

            $details['icon'] = ' class="' . trim($icon) . '"';
        }
        $details['toggle_swap'] = isset($details['toggle_swap']) ? $details['toggle_swap'] : 'btn-danger btn-success fa-thumbs-down fa-thumbs-up';

        $details['class']       = isset($details['class']) ? $details['class'] : 'btn far btn-success fa-thumbs-up toggleRight';

        $details['class']       .= empty($details['mergeClass']) ? '' : ' ' . $details['mergeClass'];

        $details['label']       = empty($details['label']) ? '' : $details['label'];

        $details['style']       = isset($details['style']) ? $details['style'] : 'float: right; ';

        $details['callable']    = isset($details['callable']) ? 'toggle_call="' . $details['callable'] . '"' : '';

        $toggle = str_replace(
            ['%toggle_swap%', '%class%', '%id%', '%name%', '%value%', '%label%', '%style%', '%callable%', '%icon%'],
            [$details['toggle_swap'], $details['class'], $details['id'], $details['name'], $details['value'], $details['label'], $details['style'], $details['callable'], $details['icon']],
            $toggle
        );

        return $toggle;
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function onButton($details = []): string
    {
        return $this->generateButton($this->buttons['on'], $details);
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function offButton($details = []): string
    {
        return $this->generateButton($this->buttons['off'], $details);
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function onOffButton($details = []): string
    {
        if (!isset($details['value']) && ! is_bool($details['value']))
            throw new \InvalidArgumentException('You must set a boolean value for the On/Off Button.  value = ?');
        $details['on'] = isset($details['on']) ? $details['on'] : [];
        $details['off'] = isset($details['off']) ? $details['off'] : [];
        if (isset($details['title']) && is_string($details['title']))
        {
            $details['on']['title'] = isset($details['on']['title'])? $details['on']['title'] : $details['title'].'.on';
            $details['off']['title'] = isset($details['off']['title'])? $details['off']['title'] : $details['title'].'.off';
            unset($details['title']);
        }

        if (isset($details['title']) && is_array($details['title']))
        {
            $details['on']['title']['message'] = isset($details['on']['title']['message'])? $details['on']['title']['message'] : $details['title']['message'].'.on';
            $details['off']['title']['message'] = isset($details['off']['title']['message'])? $details['off']['title']['message'] : $details['title']['message'].'.off';

            $details['on']['title']['params'] = $details['off']['title']['params'] = $details['title']['params'];
            unset($details['title']);
        }

        $on = $details['on'];
        $off = $details['off'];
        unset($details['on'], $details['off']);
        $details['on'] = array_merge($on, $details);
        $details['off'] = array_merge($off, $details);
        if ($details['value'])
            return $this->generateButton($this->buttons['on'], $details['on']);
        else
            return $this->generateButton($this->buttons['off'], $details['off']);
    }

    /**
     * @param array $details
     *
     * @return string
     */
    public function duplicateButton($details = []): string
    {
        return $this->generateCollectionButton($this->buttons['duplicate'], $details);
    }

    /**
     * @param $defaults
     * @param $details
     * @return string
     */
    private function generateCollectionButton($defaults, $details): string
    {
        if (! empty($details) && ! empty($details['collection']))
        {
            if ($details['collection'] instanceof FormView)
            {
                $name = $details['collection']->vars['id'];
                $defaults['class'] = str_replace('collection', $name.'-collection', $defaults['class'] ?: '');
            }
            if (is_string($details['collection']))
            {
                $name = $details['collection'];
                $defaults['class'] = str_replace('collection', $name.'-collection', $defaults['class'] ?: '');
            }
            unset($details['collection']);
        }

        return $this->generateButton($defaults, $details);
    }

    /**
     * @param array $defaults
     * @param array $details
     *
     * @return string
     */
    private function generateButton($defaults, $details = []): string
    {
        $button = '<button name="%name%" title="%title%" type="%type%" class="%class%" style="%style%" %attr%>%prompt%<span%icon%></span></button>';

        if (isset($details['class'])) {
            $defaults['class'] = $details['class'];
            unset($details['class']);
        }

        if (isset($details['mergeClass']) && isset($defaults['class']))
                $defaults['class'] .= ' ' . $details['mergeClass'];

        if (isset($details['icon'])) {
            if (is_array($details['icon'])) {
                $icon = '';
                foreach($details['icon'] as $name=>$value)
                    $icon .= $name . '="' . $value . '" ';
                $defaults['icon'] = ' ' . trim($icon);
            }
            unset($details['icon']);
        } else {
            $icon = '';
            if (preg_match('#^far | far | far$#', $defaults['class'], $matches) > 0) {
                $icon .= 'far ';
                $defaults['class'] = str_replace($matches, '', $defaults['class']);
            }

            if (preg_match('#^fas | fas | fas$#', $defaults['class'], $matches) > 0) {
                $icon .= 'fas ';
                $defaults['class'] = str_replace($matches, '', $defaults['class']);
            }

            if (preg_match('#^fal | fal | fal$#', $defaults['class'], $matches) > 0) {
                $icon .= 'fal ';
                $defaults['class'] = str_replace($matches, '', $defaults['class']);
            }

            if (preg_match('#^fab | fab | fab$#', $defaults['class'], $matches) > 0) {
                $icon .= 'fab ';
                $defaults['class'] = str_replace($matches, '', $defaults['class']);
            }

            if (preg_match_all('#fa-([\S][\w-]*)#', $defaults['class'], $matches) > 0) {
                foreach($matches[0] as $match)
                    $icon .= $match . ' ';
                $defaults['class'] = str_replace($matches[0], '', $defaults['class']);
            }

            $defaults['icon'] = ' class="' . trim($icon) . '"';
        }

        $defaults['class'] = trim(str_replace('  ', ' ', $defaults['class']));

        if (! empty($details['additional']))
            $details['attr'] = $details['additional'];

        if (isset($details['attr']) && is_array($details['attr']))
        {
            $attr            = $details['attr'];
            $details['attr'] = '';
            foreach ($attr as $name => $value)
                $details['attr'] .= $name . '="' . $value . '" ';
            $details['attr'] = trim($details['attr']);
        }

        if (isset($details['disabled']) && $details['disabled'])
            $details['attr'] =  isset($details['attr']) ? $details['attr'] . ' disabled' : 'disabled';

        if (!empty($details['windowOpen']))
        {
            if (isset($details['windowOpen']['route_params']))
            {
                $details['windowOpen']['route'] = $this->router->generate($details['windowOpen']['route'], $details['windowOpen']['route_params']) ;
            }
            $target                = empty($details['windowOpen']['target']) ? '_self' : $details['windowOpen']['target'] ;
            $route                 = 'onClick="window.open(\'' . $details['windowOpen']['route'] . '\',\'' . $target . '\'';
            $route                 = empty($details['windowOpen']['params']) ? $route . ')"' : $route . ',\'' . $details['windowOpen']['params'] . '\')"';
            $details['attr'] = empty($details['attr']) ? $route : trim($details['attr'] . ' ' . $route);
        }

        if (!empty($details['javascript']))
        {
            $target = '';
            if (!empty($details['javascript']['options']))
            {
                foreach ($details['javascript']['options'] as $option)
                    $target .= '\'' . $option . '\',';
            }
            $target = trim($target, ',');

            $route                 = 'onClick="' . $details['javascript']['function'] . '(' . $target . ');"';
            $details['attr'] = empty($details['attr']) ? $route : trim($details['attr'] . ' ' . $route);
        }

        if (!empty($details['disabled']) && $details['disabled'])
            $details['attr'] = empty($details['attr']) ? 'disabled' : trim($details['attr'] . ' disabled');

        if (!isset($defaults['name']))
            $defaults['name'] = '';

        foreach ($defaults as $q => $w)
        {
            if ($q == 'attr')
                $details['attr'] = empty($details['attr']) ? $w : trim($details['attr'] . ' ' . $w);
            if (isset($details[$q]))
                $defaults[$q] = $details[$q];
            if ($q == 'name')
                $details['attr'] = empty($details['attr']) ? '' : trim($details['attr'] . ' ' . $w);
            if (empty($defaults[$q]))
            {
                unset($defaults[$q]);
                $button = str_replace(array($q . '="%' . $q . '%"', '%' . $q . '%'), '', $button);
            }
            else
            {
                if (in_array($q, ['title', 'prompt']))
                    if (is_array($defaults[$q]))
                        $defaults[$q] = $this->trans($defaults[$q]['message'], $defaults[$q]['params'], empty($details['transDomain']) ? $this->getTransDomain() : $details['transDomain']);
                    else
                        $defaults[$q] = $this->trans($defaults[$q], [], empty($details['transDomain']) ? $this->getTransDomain() : $details['transDomain']);
                $button = str_replace('%' . $q . '%', $defaults[$q], $button);
            }
        }

        if (isset($details['collectionName']))
            $button = str_replace('collection', $details['collectionName'], $button);

        if (isset($details['colour']))
            $button = str_replace(['btn-default', 'btn-success', 'btn-info', 'btn-warning', 'btn-danger', 'btn-primary', 'btn-link', 'btn-light', 'btn-dark'], 'btn-' . $details['colour'], $button);

        return $button;
    }

    /**
     * trans
     *
     * @param string $message
     * @param array $params
     * @param string $domain
     * @return null|string
     */
    private function trans(string $message, array $params, string $domain = 'FormTheme'): ?string
    {
        if (isset($params['transChoice'])) {
            $transChoice = intval($params['transChoice']);
            unset($params['transChoice']);
            $x = $this->translator->transChoice($message, $transChoice, $params, $domain);
            return $x;
        }
        return $this->translator->trans($message, $params, $domain);
    }

    /**
     * @var string
     */
    private $transDomain = 'FormTheme';

    /**
     * @return string
     */
    public function getTransDomain(): string
    {
        return $this->transDomain;
    }

    /**
     * @param string $transDomain
     * @return ButtonManager
     */
    public function setTransDomain(string $transDomain): ButtonManager
    {
        $this->transDomain = $transDomain;
        return $this;
    }
}