#REACT BOOTSTRAP SYMFONY FORM

This part of the bundle seeks to generate a form using the standards already implemented by Symfony (Version 4.1+) and Bootstrap (Version 4.1+)  The only change is the removal of the render template, replaced by a manager class that defines the template for the form to be displayed.

The bundle provides integration of some Symfony validation, uses the Symfony Form Type definitions, and uses the form handler to validate data return to the backend.

This documentation uses a build case in a project I have been working on to illustrate the process of using the **REACT BOOTSTRAP SYMFONY FORM**

###Getting Started
Like all pages in Symfony, the controller is used to create a route and pass off to the various managers to collect the data so the view can be rendered.  The controller code:

    /**
     * editColumn
     *
     * @param TimetableColumnManager $manager
     * @param $id
     * @param string $tabName
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/timetable/column/{id}/edit/{tabName}", name="edit_column")
     * @Security("is_granted('USE_ROUTE', ['manage_columns'])")
     */
    public function editColumn(TimetableColumnManager $manager, $id, $tabName = 'details')
    {
        $manager->find($id);

        $form = $this->createForm(TimetableColumnType::class, $manager->getEntity());

        return $this->render(
            'Timetable/column_edit.html.twig',
            [
                'form' => $form,
                'manager' => $manager,
                'tabName' => $tabName,
            ]
        );
    }
The **TimetableColumnManager** is an entity manager/provider that holds entity, but you can do this as you normally do. The TimetableColumnManager implements the **Hillrange\Form\Util\FormManagerInterface**, required to define template for the form.

The controller passes the created form, generated in the usual manner to the the render.  Note the the full form (*Symfony\Component\Form\FormInterface*) is passed to the render, not the usual formView (*Symfony\Component\Form\FormView*).  The formView is rendered within the template.  As example the render template is:

    {% trans_default_domain "Timetable" %}
       
    {% extends "Default/template.html.twig" %}
    
    {% block title %}{{ parent() }}{{ 'Edit Timetable Column'|trans }}{% endblock title %}
    {% block headerTitle %}{{ 'Edit Timetable Column'|trans }}{% endblock headerTitle %}
    {% block headerLead %}{% endblock headerLead %}
    
    {% block contentContainer %}
       {% include 'Default/wait.html.twig' %}
    {% endblock contentContainer %}
    
    {% block javascripts %}
       {{ parent() }}
       {{ renderForm(form, manager, 'columnTemplate')|raw }}
    {% endblock javascripts %}


and the only part of the *Default/template.html.twig* that is important for defining form is:

    <div id="pageContent">
        {% block contentContainer %}
            {{ 'No content'|trans }}
        {% endblock contentContainer %}
    </div>
which defines to target for the REACT content.  **id="pageContent"**.  Using the template that I will define below, this results in the following display.
![REACT Display Tab](ReactRender1.jpg)  
Image One

So lets start the deep dive into the template definition.

###Template Definition
The render process is handled by the call to **renderForm** in the twig template above.  The renderForm method is passed three attributes, the FormInterface, the TemplateManager (FormManagerInterface) and the name of the template. (*This is set for future expansion to allow multiple forms on a single page.*)  


The TemplateManager must provide a property equal to the name of the template (columnTemplate) or a getter for the template name.  In this case the getter in the TemplateManager is:

    /**
     * getColumnTemplate
     *
     * @return array
     */
    public function getColumnTemplate(): array
    {
        return [
            'form' => [
                'url' => '/timetable/column/{id}/save/',
                'url_options' => [
                    '{id}' => 'id'
                ],
            ],
            'tabs' => [
                'details' => $this->getDetailsTab(),
                'columnRow' => $this->getColumnRowsTab(),
            ],
        ];
    }
The template is defined as follows. The template must have one of this optional tabs or container:

* **form** *(Mandatory)*
    * url *(Mandatory)*: The url path of the route used to save the form.
    * url_options *(optional)*: array of options that are used to modify the url for the specific entity details.
    * method *(optional)*: defaults to post. Allowed values are:
        * post
        * get
    * encType *(optional)*: defaults to application/x-www-form-urlencoded' Allowed values are:
        * application/x-www-form-urlencoded
        * text/plain
        * multipart/form-data
* **tabs** *(optional)*
    * name *(Mandatory)*: The name of the tab.  Must be unique.
    * container *(Mandatory)*: See details for a [Container](#container).
    * label *(optional)*: Defaults to false, This label is translated and displayed on the tab.
    * label_params *(optional)*: Defaults to an empty array, but defines translation parameters for the label.
    * display *(optional)*: Defaults to true, or can be the name of a function in the TemplateManager that returns a boolean value. 
* **container** *(optional)*: See details for a [Container](#container). 

####Container
The container is used to hold the template of a page or tab.  It can have the following definition.  All options in a container default to false:
* panel *(optional)*: 
    * label *(Mandatory)*: This label is translated and displayed on the panel header.
    * colour *(optional)*: Defaults to info.  Uses the colours as defined in the bootstrap cards as per [Bootstrap Colours.](https://getbootstrap.com/docs/4.1/utilities/colors/)
    * description *(optional)*: Defaults to false, and is translated as a paragraph under the panel header.
    * buttons *(optional)*: Defaults to false.  Can be set an and array of buttons.  See [Button Definition](#button-definition) for details to define a button. 
    * label_params *(optional)*: Defaults to an empty array, but defines translation parameters for the label.
    * description_params *(optional)*: Defaults to an empty array, but defines translation parameters for the description.
    * rows *(optional)*: Defaults to an empty array, but required if a collection is not defined. Defined as per the Row Definition.
    * collectionRows *(optional)*: Defaults to false. These rows are repeated for a collection data and are defined as per the [Row Definition](#row-definition).
    * headerRow *(optional)*: A header row.  Defaults to false See [Row Definition](#row-definition).
* class *(optional)*: Defaults to false. The class (React className) applied to the container \<div> element.
* rows *(optional)*: Defaults to false.  If panel or collection is not defined, then must be defined.  See [Row Definition](#row-definition).
* headerRow *(optional)*: A header row.  Defaults to false See Row Definition.
* collection *(optional)*: Defaults to false. An array defined in Collection Definition.
 
####Row Definition
Each row must have the following mandatory parameters:
* class: The class (React className) applied to the row \<div> element.
* columns: An array of columns within the row. See [Column Definition](#column-definition)

####Column Definition
All of the parameters for a Column are optional.
* form: Defaults to false or an array of the form name => style, where the style = row ow widget.
* label: This label is translated and displayed on the column header.  Mostly used in a header row member.
* label_params: Defaults to an empty array, but defines translation parameters for the label.
* class: Defaults to false. The class (React className) applied to the column \<div> element. e.g. col-2
* buttons: Defaults to false.  Can be set an array of buttons.  See [Button Definition](#button-definition) for details to define a button. 
* container: See details for a [Container](#container). NB: a recursive call to an container to be built inside the column.
* rows: See details for a [Row Definition](#row-definition). NB: a recursive call to rows to be built inside the column.
* collection_actions: Action buttons to be applied to each member of a collection.  If set to true, all the buttons found in this column are rendered for the collection member.

####Button Definition
* type *(Mandatory)*: a string that selects the type of button. Allowed values are:
    * save: The save button uses a url to push data to the backend.
    * ~~submit~~: not currently implemented.
    * add: Add a member to a collection.  Can use a url to do backend work as required.
    * delete: Delete a member of a collection. Can use a url to do backend work as required.
    * return: Return to a previous page using a url.
    * close: Close the current page.
    * ~~duplicate~~: Not currently implemented. Duplicate a member of a collection using a url for backend work as required.
* mergeClass *(optional)*: Defaults to '' (empty string.) Merge this string into button element class.  
* style *(optional)*: Defaults to false, or an array of style types as defined by REACT (so use camelCase names.) e.g. backgroundColor => 'red'
* options*(optional)*: An array of details about the form element associated with this button.  The only option avaialable is eid = element identifier.  For a collection, the eid can be set to the name or the id or the full_name of the collection member.  (Smoke and Mirrors.)
* url *(optional)*: The url path of the route used by this button.
* url_options *(optional)*: array of options that are used to modify the url for the specific entity details.
* url_type *(optional)*: Defaults to 'json', or can be 'redirect'
    * json: Does a json ajax call to the backend and returns the form and messages.
    * redirect: Open a page with the url given. (Replace the page.)
* display *(optional)*: defaults to true. Add a method name in the TemplateManager to return a boolean result. 

####Collection Definition
To use collections in the react form render, you must use the __*ReactCollectionType*__. This adds functionality to the standard *CollectionType* to better handle collection members.  Please ensure that you use this type when you define your Collection, so that the functionality is correctly added so the renderer works correctly.  One of the additions is the use of option **sort_manage**, that sets both *allow_up*, and *allow_down* in the standard CollectionType
* form *(Mandatory)*: an array of the form name => style, where the style = row ow widget.
* rows *(Mandatory)*: See [Row Definition](#row-definition).
* buttons *(optional)*: Defaults to false.  Can be set an array of buttons.  See [Button Definition](#button-definition) for details to define a button. 
* sortBy *(optional)*: Defaults to false. An array (max count of 3) of collection child names with disrection of sort. 
 

    
###Example 1 ( a Tab)
Render Image One above

    /**
     * getDetailsTab
     *
     * @return array
     */
    private function getDetailsTab(): array
    {
        return [
            'name' => 'details',
            'label' => 'Details',
            'container' => [
                'panel' => [
                    'colour' => 'info',
                    'label' => 'Manage Timetable Column',
                    'buttons' => [
                        [
                            'type' => 'save',
                        ],
                        [
                            'type' => 'return',
                            'url' => '/timetable/column/list/',
                            'url_type' => 'redirect',
                        ],
                    ],
                    'rows' => [
                        [
                            'class' => 'row',
                            'columns' => [
                                [
                                    'class' => 'col-4 card',
                                    'form' => ['name' => 'row'],
                                ],
                                [
                                    'class' => 'col-4 card',
                                    'form' => ['nameShort' => 'row'],
                                ],
                                [
                                    'class' => 'col-4 card',
                                    'form' => ['dayOfWeek' => 'row'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

###Example 2 ( a Tab)

    /**
     * getColumnRowsTab
     *
     * @return array
     */
    private function getColumnRowsTab(): array
    {
        return [
            'name' => 'rows',
            'label' => 'Column Rows',
            'container' => [
                'panel' => [
                    'colour' => 'info',
                    'label' => 'Timetable Column Rows: %{name}',
                    'label_params' => ['%{name}' => $this->getEntity()->getName()],
                    'buttons' => [
                        [
                            'type' => 'save',
                        ],
                        [
                            'type' => 'return',
                            'url' => '/timetable/column/list/',
                            'url_type' => 'redirect',
                        ],
                    ],
                    'rows' => [
                        [
                            'class' => 'row',
                            'columns' => [
                                [
                                    'container' => [
                                        'class' => 'container',
                                        'headerRow' => [
                                            'class' => 'row row-header text-center',
                                            'columns' => [
                                                [
                                                    'class' => 'col-2',
                                                    'label' => 'Name',
                                                ],
                                                [
                                                    'class' => 'col-2',
                                                    'label' => 'Abbrev.',
                                                ],
                                                [
                                                    'class' => 'col-2',
                                                    'label' => 'Start Time',
                                                ],
                                                [
                                                    'class' => 'col-2',
                                                    'label' => 'End Time',
                                                ],
                                                [
                                                    'class' => 'col-2',
                                                    'label' => 'Column Type',
                                                ],
                                                [
                                                    'class' => 'col-2',
                                                    'label' => 'Actions',
                                                ]
                                            ],
                                        ],
                                        'collection' => [
                                            'form' => 'timetableColumnRows',
                                            'sortBy' => [
                                                'timeStart' => 'ASC',
                                            ],
                                            'buttons' => [
                                                'add' => [
                                                    'mergeClass' => 'btn-sm',
                                                    'type' => 'add',
                                                    'style' => [
                                                        'float' => 'right',
                                                    ],
                                                ],
                                                'delete' => [
                                                    'mergeClass' => 'btn-sm',
                                                    'type' => 'delete',
                                                    'url' => '/timetable/column/'.$this->getEntity()->getId().'/row/{cid}/delete/',
                                                    'url_options' => [
                                                        '{cid}' => 'data_id',
                                                    ],
                                                    'url_type' => 'json',
                                                    'options' => [
                                                        'eid' => 'name',
                                                    ],
                                                ],
                                            ],
                                            'rows' => [
                                                [
                                                    'class' => 'small row row-striped',
                                                    'columns' => [
                                                        [
                                                            'class' => 'col-2',
                                                            'form' => ['name' => 'widget'],
                                                        ],
                                                        [
                                                            'class' => 'col-2',
                                                            'form' => ['nameShort' => 'widget'],
                                                        ],
                                                        [
                                                            'class' => 'col-2',
                                                            'form' => ['timeStart' => 'widget'],
                                                        ],
                                                        [
                                                            'class' => 'col-2',
                                                            'form' => ['timeEnd' => 'widget'],
                                                        ],
                                                        [
                                                            'class' => 'col-2 text-right',
                                                            'form' => ['type' => 'widget'],
                                                        ],
                                                        [
                                                            'class' => 'hidden',
                                                            'form' => ['timetableColumn' => 'row'],
                                                        ],
                                                        [
                                                            'class' => 'col-2 text-center align-self-center',
                                                            'form' => ['id' => 'row'],
                                                            'collection_actions' => true,
                                                            'buttons' => [
                                                                'save' => [
                                                                    'mergeClass' => 'btn-sm',
                                                                    'type' => 'save',
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
    
![REACT Collection Tab](ReactRender2.jpg)

####Saving a Form Data to the Backend

    /**
     * editColumnSave
     *
     * @param int $id
     * @param Request $request
     * @param FormManager $formManager
     * @param TimetableColumnManager $manager
     * @return JsonResponse
     * @throws \Exception
     * @Route("/timetable/column/{id}/save/", name="edit_column_save")
     * @Security("is_granted('USE_ROUTE', ['manage_columns'])")
     */
    public function editColumnSave(int $id, Request $request, FormManager $formManager, TimetableColumnManager $manager)
    {
        $entity = $manager->find($id);

        $form = $this->createForm(TimetableColumnType::class, $entity);

        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if ($form->isValid())
        {
            $manager->getEntityManager()->persist($entity);
            $manager->getEntityManager()->flush();
        }

        return new JsonResponse(
            [
                'form' => $formManager->extractForm($form),
                'messages' => $formManager->getFormErrors($form),
            ],
            200);
    }
For the example I have separated out the save logic.  The data here is posted via a json, and therefore the data is not stored in the Request in the same manner, so the form uses the submit, rather than handleRequest. This applies all of the standard Symfony logic to escape inputs for security and cross site attack and validation.   
The FormManager has methods to extract the form in the correct format for the react render script.  
####Message Management
Messages need to be returned as an array with each message in the format of:
* message: translated message to be displayed.
* level: The status level (danger, success, warning, info) using bootstrap colour settings to indicate the message status.  
