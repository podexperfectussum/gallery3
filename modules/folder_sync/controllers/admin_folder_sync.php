<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2012 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */
class Admin_Folder_Sync_Controller extends Admin_Controller {
  public function index() {
    $view = new Admin_View("admin.html");
    $view->page_title = t("Add from server");
    $view->content = new View("admin_folder_sync.html");
    $view->content->form = $this->_get_admin_form();
    $view->content->form_additional = $this->_get_admin_form_additional();
    $paths = unserialize(module::get_var("folder_sync", "authorized_paths", "a:0:{}"));
    $view->content->paths = array_keys($paths);

    print $view;
  }

  public function add_path() {
    access::verify_csrf();

    $form = $this->_get_admin_form();
    $paths = unserialize(module::get_var("folder_sync", "authorized_paths", "a:0:{}"));
    if ($form->validate()) {
      if (is_link($form->add_path->path->value)) {
        $form->add_path->path->add_error("is_symlink", 1);
      } else if (!is_readable($form->add_path->path->value)) {
        $form->add_path->path->add_error("not_readable", 1);
      } else {
        $path = $form->add_path->path->value;
        $paths[$path] = 1;
        module::set_var("folder_sync", "authorized_paths", serialize($paths));
        message::success(t("Added path %path", array("path" => $path)));
        folder_sync::check_config($paths);
        url::redirect("admin/folder_sync");
      }
    }

    $view = new Admin_View("admin.html");
    $view->content = new View("admin_folder_sync.html");
    $view->content->form = $form;
    $view->content->paths = array_keys($paths);
    print $view;
  }

  public function save_options() {
    access::verify_csrf();
    $form = $this->_get_admin_form_additional();
    if($form->validate()) {
      module::set_var("folder_sync", "skip_duplicates", $form->addition_options->skip_duplicates->checked);
      module::set_var("folder_sync", "process_updates", $form->addition_options->process_updates->checked);
    }
    url::redirect("admin/folder_sync");
  }

  public function remove_path() {
    access::verify_csrf();

    $path = Input::instance()->get("path");
    $paths = unserialize(module::get_var("folder_sync", "authorized_paths"));
    if (isset($paths[$path])) {
      unset($paths[$path]);
      message::success(t("Removed path %path", array("path" => $path)));
      module::set_var("folder_sync", "authorized_paths", serialize($paths));
      folder_sync::check_config($paths);
    }
    url::redirect("admin/folder_sync");
  }

  public function autocomplete() {
    $directories = array();
    $path_prefix = Input::instance()->get("q");
    foreach (glob("{$path_prefix}*") as $file) {
      if (is_dir($file) && !is_link($file)) {
        $directories[] = $file;
      }
    }

    print implode("\n", $directories);
  }

  private function _get_admin_form() {
    $form = new Forge("admin/folder_sync/add_path", "", "post",
                      array("id" => "g-server-add-admin-form", "class" => "g-short-form"));
    $add_path = $form->group("add_path");
    $add_path->input("path")->label(t("Path"))->rules("required")->id("g-path")
      ->error_messages("not_readable", t("This directory is not readable by the webserver"))
      ->error_messages("is_symlink", t("Symbolic links are not allowed"));
    $add_path->submit("add")->value(t("Add Path"));

    return $form;
  }

  private function _get_admin_form_additional() {
    $form = new Forge("admin/folder_sync/save_options", "", "post",
                      array("id" => "g-server-add-admin-form"));

    $group = $form->group("addition_options")->label(t("Additional options"));
    $group->checkbox("skip_duplicates")->label(t("Skip duplicates?"))->id("g-server-add-skip-duplicates")
      ->checked(module::get_var("folder_sync", "skip_duplicates", false));
    $group->checkbox("process_updates")->label(t("Process updates?"))->id("g-server-add-process-updates")
      ->checked(module::get_var("folder_sync", "process_updates", false));
    $group->submit("save")->value(t("Save"));

    return $form;
  }
}