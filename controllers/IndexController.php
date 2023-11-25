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
    $rows = $this->_options();
  }

  public function editAction()
  {
    // Get the requested option.
    $option = $this->_helper->db->findById();

    // Get form values from the options record.
    $values = @unserialize($option);
    $formData["id"] = $option["id"];
    $formData["exhibit_id"] = $values["exhibit_id"];
    $formData["collection_id"] = $values["collection_id"];
    $formData["current_user"] = current_user()->id;
    $formData["inserted"] = isset($values["inserted"])
      ? $values["inserted"]
      : "";
    $formData["updated"] = date("Y-m-d H:i:s");

    $form = $this->_getForm($formData);

    $this->view->form = $form;

    $this->_processMicrositeExhibitForm($option, $form, "edit");
  }

  protected function _options()
  {
    $rows = [];
    $db = get_db();
    $sql = "SELECT * FROM `{$db->prefix}options` WHERE 1 AND name REGEXP 'exhibit_microsite\\[\[0-9]+\\]'";
    $result = $db->getTable("Option")->fetchAll($sql);
    if ($result) {
      foreach ($result as $row) {
        $values = $this->_browse_data($row);
        $rows["option_id"] = $row["id"];
        $rows[$values["slug"]] = $values;
      }
      ksort($rows);
    }
    return $rows;
  }

  protected function _browse_data($row)
  {
    $db = get_db();
    $row["value"] = @unserialize($row["value"]);
    $sql = "SELECT `title`,`slug` FROM {$db->prefix}exhibits WHERE `id` = '{$row["value"]["exhibit_id"]}'";
    $exhibit = $db->getTable("Exhibit")->fetchRow($sql);
    if (isset($exhibit["title"])) {
      $row["exhibit"] = $exhibit;
    }

    return [
      "option_id" => $row["id"],
      "exhibit_title" => $row["exhibit"]["title"],
      "slug" => $row["exhibit"]["slug"],
    ];
  }

  public function addAction()
  {
    // Create a new option.
    $option = new Option();
    $form = $this->_getForm();
    $this->view->form = $form;
    $this->_processMicrositeExhibitForm($option, $form, "add");
  }

  protected function _getForm($option = null)
  {
    $formOptions = ["type" => "option"];
    $form = new Omeka_Form_Admin($formOptions);
    $exhibits = $this->_exhibits();
    $collections = $this->_collections();

    $form->addElementToEditGroup("select", "exhibit_id", [
      "id" => "exhibit-microsite-exhibit",
      "class" => "exhibit-microsite-options",
      "value" => isset($option["exhibit_id"]) ? $option["exhibit_id"] : "",
      "data-record_type" => "exhibit",
      "label" => __("Exhibit"),
      "description" => __(
        "Select an exhibit for the microsite. Note: The exhibit must already exist."
      ),
      "required" => true,
      "multiOptions" => $exhibits,
    ]);

    $form->addElementToEditGroup("multicheckbox", "collection_id", [
      "id" => "exhibit-microsite-collection",
      "class" => "exhibit-microsite-options",

      "multiOptions" => $collections,
      "value" => isset($option["collection_id"])
        ? $option["collection_id"]
        : [],
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

  private function _process_option($option_name, $option)
  {
    set_option($option_name, $option);

    if (get_option($option_name) == $option) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Process the ExhibitMicrosite add and edit forms.
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
        $exhibit_id = $_POST["exhibit_id"];
        $option->name = "exhibit_microsite[" . $exhibit_id . "]";
        $option->value = [
          "exhibit_id" => $_POST["exhibit_id"],
          "collection_id" => $_POST["collection_id"],
        ];

        $data = @unserialize($option->value);

        if (get_option($option->name)) {
          $action = "edit";
          $option->value["modified_by_user_id"] = current_user()->id;
          $option->value["created_by_user_id"] = $data["created_by_user_id"];
          $option->value["inserted"] = $data["inserted"];
          $option->value["updated"] = date("Y-m-d H:i:s");
        } else {
          $action = "add";
          $option->value["created_by_user_id"] = current_user()->id;
          $option->value["created_by_user_id"] = current_user()->id;
          $option->value["inserted"] = date("Y-m-d H:i:s");
          $option->value["updated"] = date("Y-m-d H:i:s");
        }

        $option->value = serialize($option->value);

        if ($this->_process_option($option->name, $option->value)) {
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
