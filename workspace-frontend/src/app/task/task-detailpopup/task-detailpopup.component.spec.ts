import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TaskDetailpopupComponent } from './task-detailpopup.component';

describe('TaskDetailpopupComponent', () => {
  let component: TaskDetailpopupComponent;
  let fixture: ComponentFixture<TaskDetailpopupComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TaskDetailpopupComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TaskDetailpopupComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
