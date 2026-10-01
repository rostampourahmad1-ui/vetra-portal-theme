<?php
/**
 * Vetra Plus roles and capabilities.
 *
 * @package VetraPlus
 */
namespace Vetra\Plus;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Capabilities {
	public static function all() {
		return array(
			'edit_vetra_project',
			'read_vetra_project',
			'delete_vetra_project',
			'edit_vetra_projects',
			'edit_others_vetra_projects',
			'publish_vetra_projects',
			'read_private_vetra_projects',
			'delete_vetra_projects',
			'delete_private_vetra_projects',
			'delete_published_vetra_projects',
			'edit_private_vetra_projects',
			'edit_published_vetra_projects',
			'manage_vetra_project_terms',
		);
	}

	public static function sync() {
		$manager_caps = array_fill_keys( self::all(), true );
		$editor_caps = array(
			'edit_vetra_project'        => true,
			'read_vetra_project'        => true,
			'edit_vetra_projects'       => true,
			'publish_vetra_projects'    => true,
			'edit_published_vetra_projects' => true,
			'upload_files'              => true,
		);
		$roles = array(
			'vetra_project_editor'  => array( 'name' => 'ویرایشگر پروژه وترا', 'caps' => $editor_caps ),
			'vetra_project_manager' => array( 'name' => 'مدیر پروژه وترا', 'caps' => $manager_caps ),
		);
		foreach ( $roles as $slug => $role ) {
			$object = get_role( $slug );
			if ( ! $object ) {
				$object = add_role( $slug, $role['name'], $role['caps'] );
			}
			if ( $object ) {
				foreach ( $role['caps'] as $cap => $grant ) {
					if ( $grant ) {
						$object->add_cap( $cap );
					}
				}
			}
		}
		$administrator = get_role( 'administrator' );
		if ( $administrator ) {
			foreach ( $manager_caps as $cap => $grant ) {
				if ( $grant ) {
					$administrator->add_cap( $cap );
				}
			}
		}
	}
}
