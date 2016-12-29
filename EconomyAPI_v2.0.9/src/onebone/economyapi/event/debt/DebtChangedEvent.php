<?php

/*
 * EconomyS, the massive economy plugin with many features for PocketMine-MP
 * Copyright (C) 2013-2015  onebone <jyc00410@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace onebone\economyapi\event\debt;


use onebone\economyapi\EconomyAPI;
use onebone\economyapi\event\EconomyAPIEvent;

/**
 * @deprecated
 */
class DebtChangedEvent extends EconomyAPIEvent{
	private $username, $debt;
	public static $handlerList;

	public function __construct(EconomyAPI $plugin, $username, $debt, $issuer){
		parent::__construct($plugin, $issuer);
		$this->username = $username;
		$this->debt = $debt;
	}

	/**
	 * @return string
	 */
	public function getUsername(){
		return $this->username;
	}

	/**
	 * @return float
	 */
	public function getDebt(){
		return $this->debt;
	}
}