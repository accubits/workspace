import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SingleLinePreviewComponent } from './single-line-preview.component';

describe('SingleLinePreviewComponent', () => {
  let component: SingleLinePreviewComponent;
  let fixture: ComponentFixture<SingleLinePreviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SingleLinePreviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SingleLinePreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
