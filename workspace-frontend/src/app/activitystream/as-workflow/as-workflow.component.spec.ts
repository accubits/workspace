import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AsWorkflowComponent } from './as-workflow.component';

describe('AsWorkflowComponent', () => {
  let component: AsWorkflowComponent;
  let fixture: ComponentFixture<AsWorkflowComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AsWorkflowComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AsWorkflowComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
