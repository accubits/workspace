import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TaskEditpopComponent } from './task-editpop.component';

describe('TaskEditpopComponent', () => {
  let component: TaskEditpopComponent;
  let fixture: ComponentFixture<TaskEditpopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TaskEditpopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TaskEditpopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
