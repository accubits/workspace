import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CheckboxesPreviewComponent } from './checkboxes-preview.component';

describe('CheckboxesPreviewComponent', () => {
  let component: CheckboxesPreviewComponent;
  let fixture: ComponentFixture<CheckboxesPreviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CheckboxesPreviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CheckboxesPreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
