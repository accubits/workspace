import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SectionBreakComponent } from './section-break.component';

describe('SectionBreakComponent', () => {
  let component: SectionBreakComponent;
  let fixture: ComponentFixture<SectionBreakComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SectionBreakComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SectionBreakComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
