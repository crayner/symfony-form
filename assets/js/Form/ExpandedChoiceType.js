'use strict';

import React from "react"
import PropTypes from 'prop-types'
import { Checkbox } from 'react-bootstrap'



export default function ExpandedChoiceType(props) {
    const {
        element,
        elementChange,
    } = props

    let value = {...element.value}
    value = Array.from(Object.keys(value), k=>value[k]);
    element.value = value
    element.data = value

    let choices = ''
    if (typeof element.choices === 'object')
        choices = Object.keys(element.choices).map(index => {
            const option = element.choices[index]
            let style = option.attr.style !== undefined ? option.attr.style : {}
            let choiceClass = option.attr.class !== undefined ? option.attr.class + ' form-check-input' : "form-check-input"
            let input_attr = option.attr ? option.attr : {}
            if (element.value.includes(option.value))
                input_attr.checked = true
            else
                input_attr.checked = false
            return (
                <div className={"form-check align-self-center " + (element.justify === 'right' ? 'form-check-right' : 'form-check-left')} key={element.id + '_' + index + (input_attr.checked ? '_ON' : '_OFF')}>
                    <label className={'form-check-label'} htmlFor={element.id + '_' + index}>{element.justify === 'right' ? option.label : ''}
                        <input className={choiceClass} type="checkbox" id={element.id + '_' + index} name={element.full_name + '[' + index + ']'} value={option.value} onChange={(e) => elementChange(e, element.id, 'choice')} style={style} {...input_attr} />
                        {element.justify === 'left' ? option.label : ''}
                    </label>
                </div>
            )
        })
    else
        choices = element.choices.map((option, index) => {
            let style = option.attr.style !== undefined ? option.attr.style : {}
            let choiceClass = option.attr.class !== undefined ? option.attr.class + ' form-check-input' : "form-check-input"
            let input_attr = option.attr ? option.attr : {}
            if (element.value.includes(option.value))
                input_attr.checked = true
            else
                input_attr.checked = false
            return (
                <div className={"form-check align-self-center " + (element.justify === 'right' ? 'form-check-right' : 'form-check-left')} key={element.id + '_' + index + (input_attr.checked ? '_ON' : '_OFF')}>
                    <label className={'form-check-label'} htmlFor={element.id + '_' + index}>{element.justify === 'right' ? option.label : ''}
                        <input className={choiceClass} type="checkbox" id={element.id + '_' + index} name={element.full_name + '[' + index + ']'} value={option.value} onChange={(e) => elementChange(e, element.id, 'choice')} style={style} {...input_attr} />
                        {element.justify === 'left' ? option.label : ''}
                    </label>
                </div>
            )
        })

    return (
        <div className={element.justify === 'right' ? 'text-right' : 'text-left' }>{choices}</div>
    )
}

ExpandedChoiceType.propTypes = {
    element: PropTypes.object.isRequired,
    elementChange: PropTypes.func.isRequired,
}
