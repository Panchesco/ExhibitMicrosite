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
  protected $_autoCsrfProtection = true;

  protected $_browseRecordsPerPage = self::RECORDS_PER_PAGE_SETTING;

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
    $data = [];
    $sorted = [];
    $options = $this->_options();

    // Set unserialized option values to data array.
    foreach ($options as $key => $option) {
      $values = @unserialize($option["value"]);
      $exhibit = get_record_by_id("Exhibit", $values["exhibit_id"]);
      $data[$key]["id"] = $option->id;
      $data[$key]["name"] = $option->name;
      $data[$key]["exhibit_id"] = $exhibit->id;
      $data[$key]["title"] = $exhibit->title;
      $data[$key]["exhibit_slug"] = $exhibit->slug;
      $data[$key]["collection_page_title"] = $exhibit->slug;
      $data[$key]["titles_separator"] = $option->titles_separator;
      $data[$key]["modified_by_username"] =
        isset($values["modified_by_user_id"]) &&
        !empty($values["modified_by_user_id"])
          ? get_record_by_id("User", $values["modified_by_user_id"])->username
          : "";
      $data[$key]["updated"] =
        isset($values["updated"]) & !empty($values["updated"])
          ? $values["updated"]
          : null;
      $data[$key]["collection_page_title"] =
        isset($values["collection_page_title"]) &
        !empty($values["collection_page_title"])
          ? $values["collection_page_title"]
          : __("Collection");
      $data[$key]["per_page"] =
        isset($values["per_page"]) & !empty($values["per_page"])
          ? $values["per_page"]
          : get_option("per_page_public");
    }

    $sort_field = isset($_GET["sort_field"])
      ? htmlentities($_GET["sort_field"])
      : "title";

    $sort_dir = isset($_GET["sort_dir"])
      ? htmlentities($_GET["sort_dir"])
      : "a";

    if ($sort_field == "updated") {
      foreach ($data as $row) {
        $sorted['"' . $row["updated"] . '"'] = $row;
      }
    } else {
      foreach ($data as $row) {
        $key = htmlentities($row["title"]);
        $sorted['"' . $key . '"'] = $row;
      }
    }

    if ($sort_dir == "d") {
      krsort($sorted, SORT_STRING);
    } else {
      ksort($sorted, SORT_STRING);
    }

    $this->view->options = $sorted;
  }

  public function deleteAction()
  {
    $db = get_db();
    $option = $db
      ->getTable("Option")
      ->fetchObject(
        "SELECT * FROM {$db->prefix}options WHERE 1 AND id = {$this->getParam(
          "id"
        )}"
      );

    if ($option && $option->delete()) {
      $this->_helper->flashMessenger(
        __("The Exhibit Microsite has been deleted."),
        "success"
      );
    } else {
      $this->_helper->flashMessenger(
        __("There was a problem and the Exhibit Microsite was not deleted."),
        "error"
      );
    }
    $this->_helper->redirector("browse");
    return;
  }

  public function editAction()
  {
    $data = [];
    // Get the requested option.
    $option = $this->_helper->db->findById();

    // Get form values from the options record.
    $values = @unserialize($option);
    $formData["id"] = $option["id"];
    $formData["exhibit_id"] = $values["exhibit_id"];
    $formData["collection_id"] = $values["collection_id"];
    $formData["collection_page_title"] = $values["collection_page_title"];
    $formData["titles_separator"] = isset($values["titles_separator"])
      ? $values["titles_separator"]
      : "";
    $formData["per_page"] = $values["per_page"];
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
    $data = [];
    $db = get_db();
    $sql = "SELECT id FROM `{$db->prefix}options` WHERE 1 AND name REGEXP 'exhibit_microsite\\[\[0-9]+\\]'";
    $result = $db->getTable("Option")->fetchAll($sql);

    if ($result) {
      foreach ($result as $key => $row) {
        $data[] = get_record_by_id("Option", $row["id"]);
      }
    }
    return $data;
  }

  protected function _browse_data($row)
  {
    $db = get_db();

    $row["value"] = @unserialize($row["value"]);
    // Get the exhibit Title.
    $sql = "SELECT `title`,`slug` FROM {$db->prefix}exhibits WHERE `id` = '{$row["value"]["exhibit_id"]}'";
    $exhibit = $db->getTable("Exhibit")->fetchRow($sql);
    if (isset($exhibit["title"])) {
      $row["exhibit"] = $exhibit;
    }
    return [
      "option_id" => $row["id"],
      "exhibit_title" => $row["exhibit"]["title"],
      "slug" => $row["exhibit"]["slug"],
      "created_by_user_id" => isset($row["value"]["created_by_user_id"])
        ? $row["value"]["created_by_user_id"]
        : "",
      "created_by_username" =>
        isset($row["value"]["created_by_user_id"]) &&
        !empty($row["value"]["created_by_user_id"])
          ? get_record_by_id("User", $row["value"]["created_by_user_id"])
            ->username
          : "",
      "updated_by_user_id" => isset($row["value"]["updated_by_user_id"])
        ? $row["updated_by_user_id"]
        : "",
      "updated_by_username" =>
        isset($row["value"]["updated_by_user_id"]) &&
        !empty($row["value"]["updated_by_user_id"])
          ? get_record_by_id("User", $row["value"]["created_by_user_id"])
            ->username
          : "",
      "inserted" => isset($row["value"]["inserted"])
        ? $row["value"]["inserted"]
        : "",
      "updated" => isset($row["value"]["updated"])
        ? $row["value"]["updated"]
        : "",
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
    $formOptions = ["type" => "exhibit_microsite", "hasPublicPage" => false];
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
        "Select the collections to be available in the microsite."
      ),
      "required" => true,
    ]);

    $form->addElementToEditGroup("text", "collection_page_title", [
      "id" => "exhibit-microsite-collection-page-title",
      "class" => "exhibit-microsite-options",
      "value" => isset($option["collection_page_title"])
        ? $option["collection_page_title"]
        : __("Collection"),
      "data-record_type" => "collection-page-title",
      "label" => __("Collection Page Title"),
      "description" => __(
        "What title should appear on the Collection browse page?"
      ),
    ]);

    $form->addElementToEditGroup("text", "per_page", [
      "id" => "exhibit-microsite-per-page",
      "class" => "exhibit-microsite-options",
      "value" => isset($option["per_page"]) ? $option["per_page"] : "",
      "data-record_type" => "per-page",
      "label" => __(
        "Items Per Page. Leave blank to use the current value of %d as saved in the Omeka installation's Appearance  - Settings.",
        get_option("per_page_public")
      ),
      "description" => __(
        "How many items should display on the collection page before paginating?"
      ),
    ]);

    $form->addElementToEditGroup("text", "titles_separator", [
      "id" => "exhibit-microsite-collection-titles-separator",
      "class" => "exhibit-microsite-options",
      "value" => isset($option["titles_separator"])
        ? $option["titles_separator"]
        : "",
      "data-record_type" => "titles-separator",
      "label" => __("Titles Separator"),
      "description" => __(
        "What separator should be used between titles (e.g. Item Title : File Title) when items have more than one file?"
      ),
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
    SELECT c.`id`,c.`public`,et.`record_type`,et.`text`,et.`html` FROM {$db->prefix}element_texts et
    LEFT OUTER JOIN {$db->prefix}collections c ON et.record_id = c.id
    WHERE et.record_type = 'Collection'
    ORDER BY et.text ASC
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
        $exhibit_id = $_POST["exhibit_id"];
        $option->name = "exhibit_microsite[" . $exhibit_id . "]";
        $option->value = [
          "exhibit_id" => $_POST["exhibit_id"],
          "collection_id" => $_POST["collection_id"],
          "collection_page_title" => htmlentities(
            $_POST["collection_page_title"]
          ),
          "titles_separator" => htmlentities($_POST["titles_separator"]),
          "per_page" => htmlentities($_POST["per_page"]),
        ];

        $data = @unserialize($option->value);

        if (get_option($option->name)) {
          $action = "edit";
          $option->value["modified_by_user_id"] = current_user()->id;
          $option->value["created_by_user_id"] = $data["created_by_user_id"];
          $option->value["inserted"] = $data["inserted"];
          $option->value["updated"] = date("Y-m-d H:i:s");
        } elseif ("add" == "add") {
          $action = "add";
          $option->value["modified_by_user_id"] = current_user()->id;
          $option->value["updated_by_user_id"] = current_user()->id;
          $option->value["inserted"] = date("Y-m-d H:i:s");
          $option->value["updated"] = date("Y-m-d H:i:s");
        } elseif ("delete" == $action) {
          delete_option($option->name);
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
