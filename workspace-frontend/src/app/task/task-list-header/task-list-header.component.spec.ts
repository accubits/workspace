import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TaskListHeaderComponent } from './task-list-header.component';

describe('TaskListHeaderComponent', () => {
  let component: TaskListHeaderComponent;
  let fixture: ComponentFixture<TaskListHeaderComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TaskListHeaderComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TaskListHeaderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
