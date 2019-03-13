<?php

namespace PhoneBook\Controllers;

use Phalcon\Mvc\Controller;
use PhoneBook\Models\Contact;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;


class PhoneBookController extends Controller
{
    public function index($page, $limit)
    {
        $contacts = Contact::find(
            [
                'deleted=false',
                'columns' => implode(',', Contact::$SHOW_FIELDS)
            ]
        );

        $page = (new PaginatorModel(
            [
                'data' => $contacts,
                'limit' => $limit,
                'page' => $page,
            ]
        ))->getPaginate();

        $this->response->setContent(json_encode($page));

        return $this->response;
    }

    public function search($page, $limit, $search)
    {
        $search = $this->filter->sanitize($search, 'alphanum');

        $contacts = Contact::find(
            [
                'deleted=false AND (first_name LIKE "%' . $search . '%" OR last_name LIKE "%' . $search . '%")',
                'columns' => implode(',', Contact::$SHOW_FIELDS)
            ]
        );

        $page = (new PaginatorModel(
            [
                'data' => $contacts,
                'limit' => $limit,
                'page' => $page,
            ]
        ))->getPaginate();

        $this->response->setContent(json_encode($page));

        return $this->response;
    }

    public function show($id)
    {
        $contact = Contact::findFirst(
            [
                'id = ' . $id . ' AND deleted=false',
                'columns' => implode(',', Contact::$SHOW_FIELDS)
            ]
        );
        if (!$contact) {
            $this->response->setStatusCode(404);
        } else {
            $this->response->setJsonContent($contact);
        }

        return $this->response;
    }

    public function create()
    {
        $contactData = $this->request->getJsonRawBody(true);
        if (!$contactData) {
            $this->response->setStatusCode(406);
            $this->response->setJsonContent(['format' => 'Invalid json format']);
        } else {
            $contact = new Contact($contactData);
            if ($contact->save() === false) {
                $errors = ['fields' => []];
                foreach ($contact->getMessages() as $message) {
                    $errors['fields'][] = [
                        'message' => $message->getMessage(),
                        'field' => $message->getField(),
                    ];
                }
                $this->response->setStatusCode(406);
                $this->response->setJsonContent($errors);
            } else {
                $this->response->setJsonContent($contact->toArray(Contact::$SHOW_FIELDS));
            }
        }

        return $this->response;
    }

    public function delete($id)
    {
        $contact = Contact::findFirst(['id = ' . $id . ' AND deleted = 0']);

        if (!$contact) {
            $this->response->setStatusCode(404);
        } else {
            $contact->delete();
            $this->response->setJsonContent($contact->toArray(Contact::$SHOW_FIELDS));
        }

        return $this->response;
    }

    public function update($id)
    {
        $contact = Contact::findFirst(['id = ' . $id . ' AND deleted = 0']);
        $contactData = $this->request->getJsonRawBody(true);

        if (!$contact) {
            $this->response->setStatusCode(404);
        } elseif (!$contactData) {
            $this->response->setStatusCode(406);
            $this->response->setJsonContent(['format' => 'Invalid json format']);
        } else {
            if ($contact->update($contactData)) {
                $this->response->setJsonContent($contact->toArray(Contact::$SHOW_FIELDS));
            } else {
                $errors = ['fields' => []];
                foreach ($contact->getMessages() as $message) {
                    $errors['fields'][] = [
                        'message' => $message->getMessage(),
                        'field' => $message->getField(),
                    ];
                }
                $this->response->setStatusCode(406);
                $this->response->setJsonContent($errors);
            }
        }

        return $this->response;
    }
}
