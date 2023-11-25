<?php
/**
 * Exhibit Microsite
 *
 */

/**
 * The Exhibit Microsite index controller class.
 *
 * @package ExhibitMicrosite
 */
class ExhibitMicrosite_IndexController extends
  Omeka_Controller_AbstractActionController
{
  public function init()
  {
    // Set the model class so this controller can perform some functions,
    // such as $this->findById()
    $this->_helper->db->setDefaultModelName("Option");
  }

  public function indexAction()
  {
    // Always go to browse.
    $this->_helper->redirector("browse");
    return;
  }

  public function browseAction()
  {
  }

  public function addAction()
  {
    // Create a new option.
    $option = new Option();
    $form = $this->_getForm();
    $this->view->form = $form;
    $this->_processMicrositeExhibitForm($option, $form, "add");
  }

  protected function _getForm($microsite = null)
  {
    $formOptions = ["type" => "option"];
    if ($microsite) {
      $formOptions["exhibit"] = $microsite["exhibit"];
    }

    $form = new Omeka_Form_Admin($formOptions);
    $exhibits = $this->_exhibits();
    $collections = $this->_collections();

    $form->addElementToEditGroup("select", "exhibit", [
      "id" => "exhibit-microsite-exhibit",
      "class" => "exhibit-microsite-options",
      "value" => "",
      "data-record_type" => "exhibit",
      "label" => __("Exhibit"),
      "description" => __(
        "Select an exhibit for the microsite. Note: The exhibit must already exist."
      ),
      "required" => true,
      "multiOptions" => $exhibits,
    ]);

    $form->addElementToEditGroup("multicheckbox", "collection", [
      "id" => "exhibit-microsite-collection",
      "class" => "exhibit-microsite-options",
      "multiOptions" => $collections,
      "data-record_type" => "collection",
      "label" => __("Collections"),
      "description" => __(
        "Select the collections that should be included in the microsite."
      ),
      "required" => true,
    ]);

    if (class_exists("Omeka_Form_Element_SessionCsrfToken")) {
      $form->addElement("sessionCsrfToken", "csrf_token");
    }

    return $form;
  }

  /**
   * Returns and array of exhibits
   * @return array
   */
  protected function _exhibits()
  {
    $data[""] = __("Select Exhibit");
    $db = get_db();
    $sql = "SELECT id,title FROM {$db->prefix}exhibits WHERE 1 ORDER BY title ASC";
    $rows = $db->getTable("Exhibit")->fetchAll($sql);

    foreach ($rows as $key => $row) {
      $data[$row["id"]] = $row["title"];
    }
    return $data;
  }

  /**
   * Returns and array of collections
   * @return array
   */
  protected function _collections()
  {
    $db = get_db();
    $sql = "
    SELECT et.`id`,et.`text` FROM {$db->prefix}element_texts et
    WHERE 1
    AND et.record_type = 'Collection'
    AND et.`element_id` = (SELECT id FROM {$db->prefix}elements e WHERE 1 AND e.`name` = 'Title' LIMIT 1 )
    ORDER BY et.`text` ASC;
    ";

    $rows = $db->getTable("Collection")->fetchAll($sql);

    foreach ($rows as $key => $row) {
      $data[$row["id"]] = $row["text"];
    }
    return $data;
  }

  /**
   * Process the ExhibitMicrosite edit and edit forms.
   */
  private function _processMicrositeExhibitForm($option, $form, $action)
  {
    if ($this->getRequest()->isPost()) {
      if (!$form->isValid($_POST)) {
        $this->_helper->_flashMessenger(
          __("There was an error on the form. Please try again."),
          "error"
        );
        return;
      }
      try {
        //$option->setPostData($_POST);
        $exhibit_id = $_POST["exhibit"];

        $option->name = "exhibit_microsite[" . $exhibit_id . "]";
        $option->value = [
          "exhibit" => $_POST["exhibit"],
          "collection" => $_POST["collection"],
        ];

        $option->value = serialize($option->value);

        if (get_option($option->name)) {
          $action = "edit";
        } else {
          $action = "add";
        }

        set_option($option->name, $option->value);

        if ("add" == $action) {
          $this->_helper->flashMessenger(
            __("The Exhbit Microsite has been added."),
            "success"
          );
        } elseif ("edit" == $action) {
          $this->_helper->flashMessenger(
            __("The Exhibit Microsite has been edited."),
            "success"
          );
        }

        $this->_helper->redirector("browse");
        return;

        // Catch validation errors.
      } catch (Omeka_Validate_Exception $e) {
        $this->_helper->flashMessenger($e);
      }
    }
  }
} // End ExhibitMicrosite_IndexController
