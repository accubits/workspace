import { Component, OnInit } from '@angular/core';
import { DataService } from '../../shared/services/data.service';

@Component({
  selector: 'app-more-option',
  templateUrl: './more-option.component.html',
  styleUrls: ['./more-option.component.scss']
})
export class MoreOptionComponent implements OnInit {
  showMoreOption:boolean=false;
  constructor(public dataService: DataService) { }

  ngOnInit() {
  }

}
