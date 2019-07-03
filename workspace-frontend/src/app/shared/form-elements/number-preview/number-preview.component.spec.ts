import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NumberPreviewComponent } from './number-preview.component';

describe('NumberPreviewComponent', () => {
  let component: NumberPreviewComponent;
  let fixture: ComponentFixture<NumberPreviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NumberPreviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NumberPreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
