import { Component, OnInit, Input, Output , EventEmitter } from '@angular/core';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-userlist',
  templateUrl: './userlist.component.html',
  styleUrls: ['./userlist.component.scss']
})
export class UserlistComponent implements OnInit {
  @Input() data: any;
  @Output() deleteFromParent = new EventEmitter<string>();
  currentElementIndex:string;

  constructor(public dataService: DataService) { }

  ngOnInit() {
  }
  deleteElement() {
    this.deleteFromParent.emit(this.data);
  }
  activateElement() {
    this.dataService.formElementToggle.activeIndex = this.currentElementIndex;
  }

}
