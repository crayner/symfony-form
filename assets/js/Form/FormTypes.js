'use strict';

import React from "react"
import PropTypes from 'prop-types'
import { FormGroup,  FormControl } from 'react-bootstrap'
import FormLabel from './FormLabel'
import FormHelp from './FormHelp'
import FormRequired from './FormRequired'
import FormErrors from './FormErrors'
import '../../css/form.scss';
import ToggleType from './ToggleType'


export default function FormTypes(props) {
    const {
        form,
        style,
        elementChange,
        getElementData,
        ...otherProps
    } = props

    let element = {...form}
    
    let prefix = element.block_prefixes.slice(0).reverse()

    const content = prefix.find(type => {
        if (isFunction(type))
            return type
    })
    console.log(prefix,content)

    if (!content || /^\s*$/.test(content)){
        console.error('No element type found')
        console.log(prefix)
    }

    if (element.errors.length > 0) {
        element.attr.class = typeof element.attr.class !== 'undefined' ? element.attr.class.replace(' has-error', '').replace('has-error', '') + ' has-error' : 'has-error'
    } else {
        element.attr.class = typeof element.attr.class !== 'undefined' ? element.attr.class.replace(' has-error', '').replace('has-error', '') : ''
    }

    switch (content) {
        case 'hillrange_toggle':
            return toggleType()
        case 'hidden':
            return hiddenType()
        case 'choice':
            return choiceType()
        case 'text':
            return textType()
        case 'time':
            return timeType()
        default:
            return FormType()
    }

    function isFunction(type) {
        switch (type) {
            case 'hillrange_toggle':
            case 'hidden':
            case 'choice':
            case 'time':
            case 'text':
                return true
            default:
                return false
        }
    }

    function renderFormGroup(content, style, options){
        if (typeof options !== 'object')
            options = {}
        return (
            <FormGroup
                controlId={element.id}
                className={element.errors.length > 0 ? 'has-danger' : ''}
                {...options}
            >
                {content}
                <FormLabel label={element.label}/>
                <FormRequired required={element.required}/><br/>
                <FormErrors errors={element.errors}/>
                <FormHelp help={element.help}/>
            </FormGroup>
        )
    }

    function textType() {
        if (style === 'widget')
            return textTypeWidget()
        return renderFormGroup(textTypeWidget(), 'text')
    }

    function textTypeWidget(){
        return (
            <FormControl
                type="text"
                id={element.id}
                value={getElementData(element.id)}
                placeholder="Enter text"
                className={element.attr.class}
                name={element.full_name}
                onChange={((e) => elementChange(e, element.id))}
            />
        )
    }

    function FormType() {
        console.log(prefix)
        if (style === 'widget')
            return elementTypeWidget()
        return renderFormGroup(elementTypeWidget(), 'element')
    }

    function FormTypeWidget(){
        return (
            <FormControl
                type="text"
                value={element.value}
                placeholder="Enter text"
                onChange={((e) => elementChange(e, element.id))}
            />
        )
    }

    function timeType() {
        if (style === 'widget')
            return timeTypeWidget()
        return renderFormGroup(timeTypeWidget(), 'time')
    }

    function timeTypeWidget(){
        const hour = element.children[0]
        const minute = element.children[1]
        let second = 'undefined'
        if (typeof element.children[2] !== 'undefined')
            second = element.children[2]

        const width = 90 / element.children.length

        return (
            <div id={element.id} autoComplete={'off'} className={'form-inline' + (typeof element.attr.class === 'undefined' ? '' : ' ' + element.attr.class)}>
                <FormControl
                    componentClass="select"
                    id={hour.id}
                    value={getElementData(hour.id)}
                    className={hour.attr.class}
                    style={{'width': width + '%'}}
                    onChange={((e) => elementChange(e, hour.id))}
                >
                    {
                        hour.choices.map((option, index) => {
                            return (<option key={index} value={option.value}>{option.label}</option>)
                        })
                    }

                </FormControl>:
                <FormControl
                    componentClass="select"
                    id={minute.id}
                    value={getElementData(minute.id)}
                    style={{'width': width + '%'}}
                    className={minute.attr.class}
                    onChange={((e) => elementChange(e, minute.id))}
                >
                    {
                        minute.choices.map((option, index) => {
                            return (<option key={index} value={option.value}>{option.label}</option>)
                        })
                    }

                </FormControl>
                { second !== 'undefined' ?
                    <span>:<FormControl
                        componentClass="select"
                        id={second.id}
                        value={getElementData(second.id)}
                        style={{'width': width + '%'}}
                        className={second.attr.class}
                        onChange={((e) => elementChange(e, second.id))}
                    >
                        {
                            second.choices.map((option, index) => {
                                return (<option key={index} value={option.value}>{option.label}</option>)
                            })
                        }

                    </FormControl></span>
                    : ''
                }
            </div>
        )
    }

    function choiceType() {
        if (style === 'widget')
            return choiceTypeWidget()
        return renderFormGroup(choiceTypeWidget(), 'choice')
    }

    function getChoiceList(){
        if (typeof element.choices === 'object')
            return Object.keys(element.choices).map(index => {
                const option = element.choices[index]
                return (<option key={index} value={option.value}>{option.label}</option>)
            })

        return element.choices.map((option, index) => {
            return (<option key={index} value={option.value}>{option.label}</option>)
        })
    }

    function choiceTypeWidget(){
        return (
            <FormControl
                componentClass="select"
                value={getElementData(element.id)}
                placeholder={element.placeholder}
                multiple={element.multiple}
                className={element.attr.class}
                onChange={((e) => elementChange(e, element.id))}
            >
                {getChoiceList()}
            </FormControl>
        )
    }

    function hiddenType() {
        return (
            <FormControl
                type="hidden"
                value={getElementData(element.id)}
            />
        )
    }

    function toggleType() {
        console.log(element)
        return (
            <ToggleType
                element={element}
                style={style}
                value={getElementData(element.id)}
                {...otherProps}
            />
        )
    }
}

FormTypes.propTypes = {
    elementChange: PropTypes.func.isRequired,
    getElementData: PropTypes.func.isRequired,
    form: PropTypes.object.isRequired,
    style: PropTypes.string.isRequired,
}



