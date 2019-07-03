import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SectionBreakPreviewComponent } from './section-break-preview.component';

describe('SectionBreakPreviewComponent', () => {
  let component: SectionBreakPreviewComponent;
  let fixture: ComponentFixture<SectionBreakPreviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SectionBreakPreviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SectionBreakPreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
