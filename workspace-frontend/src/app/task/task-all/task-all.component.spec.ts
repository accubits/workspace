import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TaskAllComponent } from './task-all.component';

describe('TaskAllComponent', () => {
  let component: TaskAllComponent;
  let fixture: ComponentFixture<TaskAllComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TaskAllComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TaskAllComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
